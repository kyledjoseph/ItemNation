<?php

class Auth_Social
{
	/**
	 * Class initialisation
	 */
	public static function _init()
	{
		// is Opauth installed
		if (! class_exists('Opauth'))
		{
			throw new OpauthException('Opauth composer package not installed. Add "opauth/opauth" to composer.json and run a composer update.');
		}
		
		$drivers = Config::get('auth.driver', array()) and is_array($drivers) or $drivers = array($drivers);
		
		if (! in_array('Ormauth', $drivers))
		{
			throw new Exception("Ormauth auth.driver is required");
		}

		// load the auth config
		Config::load('auth', true);
		Config::load('opauth', true);
		Config::load('ormauth', true);
	}

	/**
	 * Create an OpAuth instance
	 *
	 * @param  array any call-time configuration to be used
	 * @param  bool  whether or not Opauth should run automatically
	 */
	public static function forge($config = array(), $autorun = true)
	{
		// deal with passing only the autorun value
		if (func_num_args() == 1 and is_bool($config))
		{
			$autorun = $config;
			$config  = array();
		}

		// merge the default config with the runtime config
		$config = Arr::merge(Config::get('opauth'), $config);

		// and a security salt
		if (empty($config['security_salt']))
		{
			throw new OpauthException('There is no "security_salt" defined in the opauth.php configuration file.');
		}

		// set some defaults, just in case
		isset($config['security_iteration']) or $config['security_iteration'] = 300;
		isset($config['security_timeout'])   or $config['security_timeout']   = '2 minutes';

		// 
		if (empty($config['callback_url']))
		{
			throw new OpauthException("callback_url required", 1);
		}

		// determine the name of the provider we want to call
		if (! $autorun)
		{
			// we're processing a callback
			$config['provider'] = 'Callback';
		}
		else
		{
			if (empty($config['provider']))
			{
				throw new OpauthException("Error Processing Request");
			}

			// check if we have a strategy defined for this provider
			if (! static::is_valid_strategy($config['provider']))
			{
				throw new OpauthException("Opauth strategy {$config['provider']} is not supported");
			}
		}

		// return the created Auth_Opauth object
		return new static($config, $autorun);
	}

	/**
	 * undefined_method
	 */
	protected static function is_valid_strategy($strategy)
	{
		return array_key_exists(ucfirst($strategy), Config::get('opauth.Strategy', array()));
	}

	// -------------------------------------------------------------------------

	/**
	 * Opauth configuration
	 */
	protected $config = array();

	/**
	 * Opauth instance
	 */
	protected $opauth = null;

	/**
	 * Opauth response
	 */
	protected $response = array();

	/**
	 * Construct the Auth_Opauth object
	 */
	public function __construct(Array $config, $autorun = true)
	{
		$this->config = $config;
		$this->opauth = new Opauth($config, $autorun);
	}

	/**
	 * Get a response value
	 */
	public function get($key, $default = null)
	{
		return is_array($this->response) ? Arr::get($this->response, $key, $default) : $default;
	}

	/**
	 * fetch the callback response
	 */
	protected function callback()
	{
		// fetch the response and decode it
		$this->response = Input::get('opauth', false) and $this->response = unserialize(base64_decode($this->response));

		// did we receive a response at all?
		if (! $this->response)
		{
			throw new OpauthException('no valid response received in the callback');
		}

		// did we receive one, but was it an error
		if (array_key_exists('error', $this->response))
		{
			throw new OpauthException('Authentication error: the callback returned an error auth response');
		}

		// validate the response
		if (! $this->get('auth', null) or 
			! $this->get('timestamp', null) or
			! $this->get('signature', null) or 
			! $this->get('auth.provider', null) or 
			! $this->get('auth.uid', null))
		{
			throw new OpauthException('Invalid auth response: Missing key auth response components');
		}

		if (! $this->opauth->validate(sha1(print_r($this->get('auth'), true)), $this->get('timestamp'), $this->get('signature'), $reason))
		{
			throw new OpauthException('Invalid auth response: '.$reason);
		}
	}

	/**
	 * undefined_method
	 */
	public function login()
	{
		// process the callback data
		$this->callback();

		// if there is no UID we don't know who this is
		if ($this->get('auth.uid', null) === null)
		{
			throw new OpauthException('No uid in response from the provider, so we have no idea who you are.');
		}

		// the user is authenticated, check if provider is linked
		if (Auth::check())
		{
			// get user id from auth
			list($driver_id, $user_id) = Auth::instance()->get_user_id();
			
			// get authenticated user
			if (! $user = Model_User::get_by_id($user_id))
			{
				throw new OpauthException("Could not find user model of authenticated user id: '{$user_id}'");
			}

			// get user provider and update provider access token
			if ($provider = $user->get_provider($this->get('auth.provider')))
			{
				$provider->update_access_token(
					$this->get('auth.credentials.token', null), 
					$this->auth_provider_expiration()
				);
			}

			// or link provider to user
			else
			{
				$provider = $user->link_provider(array(
					'parent_id'		=> $user->id,
					'provider' 		=> $this->get('auth.provider'),
					'uid' 			=> $this->get('auth.uid'),
					'access_token' 	=> $this->get('auth.credentials.token', null),
					'secret' 		=> $this->get('auth.credentials.secret', null),
					'expires' 		=> $this->auth_provider_expiration(),
					'refresh_token' => $this->get('auth.credentials.refresh_token', null),
					'user_id'		=> $user->id,
				));
			}
			
			return $user ?: false;
		}

		// the user exists and is linked, log them in
		if ($provider = Model_User_Provider::get_by_provider_uid($this->get('auth.provider'), $this->get('auth.uid')))
		{
			if (! isset($provider->user->id))
			{
				throw new OpauthException("Model_User_Provider found but missing Mosel_User id:{$provider->user_id}");
			}

			if (empty($provider->user->username))
			{
				$provider->user->username = $this->get('auth.info.nickname', $this->get('auth.info.email', Str::random('alnum', 16)));
				$provider->user->save();
			}

			// force a login with this user
			if (! Auth::instance()->force_login($provider->user->id))
			{
			    throw new OpauthException('This user could not be logged in.');
			}
			
			// update provider access token
			$provider->update_access_token(
				$this->get('auth.credentials.token', null), 
				$this->auth_provider_expiration()
			);

			return $provider->user ?: false;
		}

		// did the provider return email address
		if (! $this->get('auth.info.email'))
		{
			Session::set('auth-strategy', array(
				'user' => $this->get('auth.info'),
				'authentication' => array(
					'provider' 		=> $this->get('auth.provider'),
					'uid' 			=> $this->get('auth.uid'),
					'access_token' 	=> $this->get('auth.credentials.token', null),
					'secret' 		=> $this->get('auth.credentials.secret', null),
					'expires' 		=> $this->get('auth.credentials.expires', null),
					'refresh_token' => $this->get('auth.credentials.refresh_token', null),
				),
			));

			return false;
		}

		// get the user by email, or create a one
		if (! $user = Model_User::get_by_email($this->get('auth.info.email')))
		{
			$user = Model_User::forge(array(
				'username' => $this->get('auth.info.nickname', $this->get('auth.info.email', Str::random('alnum', 16))),
				'email'    => $this->get('auth.info.email', null),
				'password' => $this->get('auth.info.password', null),
			));
		}
		else
		{
			if (empty($user->username))
			{
				$user->username = $this->get('auth.info.nickname', $this->get('auth.info.email', Str::random('alnum', 16)));
			}
		}

		$user->save();

		// attach this authentication to the new user
		$provider = $user->link_provider(array(
			'parent_id'		=> $user->id,
			'provider' 		=> $this->get('auth.provider'),
			'uid' 			=> $this->get('auth.uid'),
			'access_token' 	=> $this->get('auth.credentials.token', null),
			'secret' 		=> $this->get('auth.credentials.secret', null),
			'expires' 		=> $this->auth_provider_expiration(),
			'refresh_token' => $this->get('auth.credentials.refresh_token', null),
			'user_id'		=> $user->id,
		));

		// set additional provider data
		$provider->fullname = $this->get_user_fullname();

		if (! Auth::instance()->force_login((int) $user->id))
		{
			throw new Exception("Error Processing Request", 1);
		}

		// save the user data, verify the provider, and authenticate the user
		if ($provider->save())
		{
		    return $user ?: false;
		}

		throw new OpauthException('We tried automatically creating a user but that just really did not work. Not sure why...');
		
	}

	/**
	 * undefined_method
	 */
	protected function get_user_fullname()
	{
		if ($fullname = $this->get('auth.info.name', null))
		{
			return $fullname;
		}
		
		if ($first = $this->get('auth.info.first_name', null) and $last = $this->get('auth.info.last_name', null))
		{
			return $first . ' ' . $last;
		}

		return null;
	}

	/**
	 * undefined_method
	 */
	protected function auth_provider_expiration()
	{
		if (! $date = $this->get('auth.credentials.expires', null))
		{
			return time();
		}

		if (is_numeric($date))
		{
			return $date;
		}
		
		if ($date = DateTime::createFromFormat(DateTime::ISO8601, $date))
		{
			return $date->getTimestamp();
		}
		
		if ($date = DateTime::createFromFormat('Y-m-d H:i:s', $date))
		{
			return $date->getTimestamp();
		}
		
		return time();
	}

}