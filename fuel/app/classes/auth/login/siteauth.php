<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.5
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */


class SiteUserUpdateException extends \FuelException {}

class SiteUserWrongPassword extends \FuelException {}

/**
 * SiteAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
class Auth_Login_SiteAuth extends \Auth_Login_Driver
{

	public static function _init()
	{
		\Config::load('siteauth', true, true, true);
	}

	/**
	 * @var  Database_Result  when login succeeded
	 */
	protected $user = null;

	/**
	 * 
	 */
	protected $hybridauth = null;

	/**
	 * @var  array  value for guest login
	 */
	protected static $guest_login = array(
		'id' => 0,
		'username' => 'guest',
		'group' => '0',
		'login_hash' => false,
		'email' => false
	);

	/**
	 * @var  array  SiteAuth class config
	 */
	protected $config = array(
		'drivers' => array('group' => array('SiteGroup')),
		'additional_fields' => array('profile_fields'),
	);


	/**
	 * Check for login
	 *
	 * @return  bool
	 */
	protected function perform_check()
	{
		// $hybridauth = $this->hybridauth_instance();
		// if (! Hybrid_Auth::isConnectedWith('facebook'))
		// {
		// 	return false;
		// }

		$username    = \Session::get('username');
		$login_hash  = \Session::get('login_hash');

		// only worth checking if there's both a username and login-hash
		if ( ! empty($username) and ! empty($login_hash))
		{
			if (is_null($this->user) or ($this->user['email'] != $username and $this->user != static::$guest_login))
			{
				$this->user = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
					->where('email', '=', $username)
					->from(\Config::get('siteauth.table_name'))
					->execute(\Config::get('siteauth.db_connection'))->current();
			}

			// return true when login was verified
			if ($this->user and $this->user['login_hash'] === $login_hash)
			{
				return true;
			}
		}

		// no valid login when still here, ensure empty session and optionally set guest_login
		$this->user = \Config::get('siteauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete('username');
		\Session::delete('login_hash');

		return false;
	}

	/**
	 * Check the user exists before logging in
	 *
	 * @return  bool
	 */
	public function validate_user($email = '', $password = '')
	{
		$email    = trim($email);
		$password = trim($password);

		if (empty($email) or empty($password))
		{
			return false;
		}

		$password = $this->hash_password($password);
		$this->user = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
			->where('email', '=', $email)
			->where('password', '=', $password)
			->from(\Config::get('siteauth.table_name'))
			->execute(\Config::get('siteauth.db_connection'))->current();

		return $this->user ?: false;
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @param   string
	 * @return  bool
	 */
	public function login($username_or_email = '', $password = '')
	{
		if ( ! ($this->user = $this->validate_user($username_or_email, $password)))
		{
			$this->user = \Config::get('siteauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete('username');
			\Session::delete('login_hash');
			return false;
		}

		// register so Auth::logout() can find us
		Auth::_register_verified($this);

		\Session::set('username', $this->user['email']);
		\Session::set('login_hash', $this->create_login_hash());
		\Session::instance()->rotate();
		return true;
	}





	public function social_auth($provider)
	{
		//$hybridauth = ($this->get_social_config());
		$hybridauth = $this->hybridauth_instance();

		// try to authenticate the selected $provider
		$adapter = $hybridauth->authenticate($provider);

		// grab the user profile
		$user_profile = $adapter->getUserProfile();
		$provider_uid = $user_profile->identifier;
		$email        = $user_profile->email;
		$first_name   = $user_profile->firstName;
		$last_name    = $user_profile->lastName;
		$display_name = $user_profile->displayName;
		$website_url  = $user_profile->webSiteURL;
		$profile_url  = $user_profile->profileURL;
		$password     = '';

		# 1 - check if user already have authenticated using this provider before
		$user_auth = Model_User_Auth::get_by_provider_uid($provider, $user_profile->identifier);

		# 2 - if authentication exists in the database, then we set the user as connected
		if (isset($user_auth))
		{
			return $this->force_login($user_auth->user->id);
		}

		# 3 - else, here lets check if the user email we got from the provider already exists in our database
		$user = Model_User::get_by_email($user_profile->email);

		# 4 - if authentication does not exist and email is not in use, then we create a new user 
		if (! isset($user))
		{
			$user = Model_User::create_user($email, $password, $display_name);
		}

		# 5 - create a new authentication for the user
		$user->authenticate_with([
			'provider'     => $provider,
			'provider_uid' => $provider_uid,
			'display_name' => $display_name,
			'profile_url'  => $profile_url,
			'website_url'  => $website_url,
		]);

		# 6 - friend all facebook friends who are registered
		$user->add_registered_facebook_friends();

		# 7 - log the new user in
		return $this->force_login($user->id);
	}

	public function hybridauth_instance()
	{
		if (! is_null($this->hybridauth))
		{
			return $this->hybridauth;
		}

		return new Hybrid_Auth(array(
			//"base_url" => "http://itemnation.com/user/process", 
			"base_url"   => Uri::create('user/process'),
			"debug_mode" => false,
			"debug_file" => "",

			"providers" => array(
				
				"OpenID" => array(
					"enabled" => false
				),

				"AOL"  => array( 
					"enabled" => false 
				),

				"Yahoo" => array( 
					"enabled" => false,
					"keys"    => array(
						"id" => "", 
						"secret" => ""
					)
				),

				"Google" => array( 
					"enabled" => true,
					"keys"    => array(
						"id" => "claimworks.net",
						"secret" => "BKWGDqe3pj2AxCuHKl_qR7l0"
					)
				),

				"Facebook" => array( 
					"enabled" => true,
					"keys"    => array(
						"id" => "168874813262398",
						"secret" => "5aa0c283019c1f03cc5430559d80c0de"
					)
				),

				"Twitter" => array( 
					"enabled" => true,
					"keys"    => array(
						"key" => "DRIoekFFpaUcIP8TFiynA",
						"secret" => "Ibdpl9XAT7cKPgUH3dCflQl7R6BYTJ9bDGl4UX051c",
					) 
				),

				// windows live
				"Live" => array( 
					"enabled" => false,
					"keys"    => array(
						"id" => "",
						"secret" => ""
					)
				),

				"MySpace" => array( 
					"enabled" => false,
					"keys"    => array(
						"key" => "",
						"secret" => ""
					)
				),

				"LinkedIn" => array( 
					"enabled" => false,
					"keys"    => array(
						"key" => "",
						"secret" => ""
					)
				),

				"Foursquare" => array(
					"enabled" => false,
					"keys"    => array(
						"id" => "",
						"secret" => ""
					)
				),
			)
		));
	}





	/**
	 * Force login user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function force_login($user_id = '')
	{
		if (empty($user_id))
		{
			return false;
		}

		$this->user = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
			->where_open()
			->where('id', '=', $user_id)
			->where_close()
			->from(\Config::get('siteauth.table_name'))
			->execute(\Config::get('siteauth.db_connection'))
			->current();

		if ($this->user == false)
		{
			$this->user = \Config::get('siteauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete('username');
			\Session::delete('login_hash');
			return false;
		}

		\Session::set('username', $this->user['email']);
		\Session::set('login_hash', $this->create_login_hash());
		return true;
	}

	/**
	 * Logout user
	 *
	 * @return  bool
	 */
	public function logout()
	{
		$this->user = \Config::get('siteauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete('username');
		\Session::delete('login_hash');
		return true;
	}

	/**
	 * Create new user
	 *
	 * @param   string
	 * @param   string  must contain valid email address
	 * @param   int     group id
	 * @return  bool
	 */
	public function create_user($email, $password)
	{
		$password = trim($password);
		$email    = filter_var(trim($email), FILTER_VALIDATE_EMAIL);

		if (empty($email))
		{
			throw new \SiteUserUpdateException('Enter your email address', 1);
		}

		if (empty($password))
		{
			throw new \SiteUserUpdateException('Choose a password', 2);
		}

		$same_users = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
			->where('email', '=', $email)
			->from(\Config::get('siteauth.table_name'))
			->execute(\Config::get('siteauth.db_connection'));

		if ($same_users->count() > 0)
		{
			throw new \SiteUserUpdateException('Email address is already registered', 3);
		}

		$hashed_password = $this->hash_password((string) $password);

		$user = Model_User::create_user($email, $hashed_password);

		return $user;
	}

	/**
	 * Update a user's properties
	 * Note: Username cannot be updated, to update password the old password must be passed as old_password
	 *
	 * @param   Array  properties to be updated including profile fields
	 * @param   string
	 * @return  bool
	 */
	public function update_user($values, $username = null)
	{
		$username = $username ?: $this->user['username'];
		$current_values = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
			->where('email', '=', $username)
			->from(\Config::get('siteauth.table_name'))
			->execute(\Config::get('siteauth.db_connection'));

		if (empty($current_values))
		{
			throw new \SiteUserUpdateException('Username not found', 4);
		}

		$update = array();
		if (array_key_exists('username', $values))
		{
			throw new \SiteUserUpdateException('Username cannot be changed.', 5);
		}
		
		if (array_key_exists('email', $values))
		{
			$email = filter_var(trim($values['email']), FILTER_VALIDATE_EMAIL);
			if ( ! $email)
			{
				throw new \SiteUserUpdateException('Email address is not valid', 7);
			}
			$update['email'] = $email;
			unset($values['email']);
		}
		if (array_key_exists('group', $values))
		{
			if (is_numeric($values['group']))
			{
				$update['group'] = (int) $values['group'];
			}
			unset($values['group']);
		}
		if ( ! empty($values))
		{
			$profile_fields = @unserialize($current_values->get('profile_fields')) ?: array();
			foreach ($values as $key => $val)
			{
				if ($val === null)
				{
					unset($profile_fields[$key]);
				}
				else
				{
					$profile_fields[$key] = $val;
				}
			}
			$update['profile_fields'] = serialize($profile_fields);
		}

		$affected_rows = \DB::update(\Config::get('siteauth.table_name'))
			->set($update)
			->where('username', '=', $username)
			->execute(\Config::get('siteauth.db_connection'));

		// Refresh user
		if ($this->user['username'] == $username)
		{
			$this->user = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
				->where('username', '=', $username)
				->from(\Config::get('siteauth.table_name'))
				->execute(\Config::get('siteauth.db_connection'))->current();
		}

		return $affected_rows > 0;
	}

	/**
	 * Change a user's password
	 *
	 * @param   string
	 * @param   string
	 * @param   string  username or null for current user
	 * @return  bool
	 */
	public function change_password($old_password, $new_password)
	{
		$current_values = \DB::select_array(\Config::get('siteauth.table_columns', array('*')))
			->where('id', '=', $this->user['id'])
			->from(\Config::get('siteauth.table_name'))
			->execute(\Config::get('siteauth.db_connection'));
			
		if (empty($old_password) or $current_values->get('password') != $this->hash_password(trim($old_password)))
		{
			return false;
		}

		return $this->set_password($new_password);
	}


	/**
	 * 
	 */
	public function set_password($password)
	{
		$password = trim(strval($password));

		if ($password === '')
		{
			throw new \SiteUserUpdateException('Password can\'t be empty.', 6);
		}

		$update['password'] = $this->hash_password($password);

		$affected_rows = \DB::update(\Config::get('siteauth.table_name'))
			->set($update)
			->where('id', '=', $this->user['id'])
			->execute(\Config::get('siteauth.db_connection'));

		return $affected_rows > 0;
	}

	/**
	 * Generates new random password, sets it for the given username and returns the new password.
	 * To be used for resetting a user's forgotten password, should be emailed afterwards.
	 *
	 * @param   string  $username
	 * @return  string
	 */
	public function reset_password($email, $new_password)
	{
		$password_hash = $this->hash_password($new_password);

		$affected_rows = \DB::update(\Config::get('siteauth.table_name'))
			->set(array('password' => $password_hash))
			->where('email', '=', $email)
			->execute(\Config::get('siteauth.db_connection'));

		if ( ! $affected_rows)
		{
			throw new \SiteUserUpdateException('Failed to reset password, user was invalid.', 8);
		}

		return true;
	}

	/**
	 * Deletes a given user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function delete_user($username)
	{
		if (empty($username))
		{
			throw new \SiteUserUpdateException('Cannot delete user with empty username', 9);
		}

		$affected_rows = \DB::delete(\Config::get('siteauth.table_name'))
			->where('username', '=', $username)
			->execute(\Config::get('siteauth.db_connection'));

		return $affected_rows > 0;
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		if (empty($this->user))
		{
			throw new \SiteUserUpdateException('User not logged in, can\'t create login hash.', 10);
		}

		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1(\Config::get('siteauth.login_hash_salt').$this->user['email'].$last_login);

		\DB::update(\Config::get('siteauth.table_name'))
			->set(array('last_login' => $last_login, 'login_hash' => $login_hash))
			->where('email', '=', $this->user['email'])
			->execute(\Config::get('siteauth.db_connection'));

		$this->user['login_hash'] = $login_hash;

		return $login_hash;
	}

	/**
	 * Get the user's ID
	 *
	 * @return  Array  containing this driver's ID & the user's ID
	 */
	public function get_user_id()
	{
		if (empty($this->user))
		{
			return false;
		}

		return (int) $this->user['id'];
	}

	/**
	 * Get the user's groups
	 *
	 * @return  Array  containing the group driver ID & the user's group ID
	 */
	public function get_groups()
	{
		if (empty($this->user))
		{
			return false;
		}

		return array(array('SiteGroup', $this->user['group']));
	}

	/**
	 * Get the user's emailaddress
	 *
	 * @return  string
	 */
	public function get_email()
	{
		if (empty($this->user))
		{
			return false;
		}

		return $this->user['email'];
	}

	/**
	 * Get the user's screen name
	 *
	 * @return  string
	 */
	public function get_screen_name()
	{
		if (empty($this->user))
		{
			return false;
		}

		return $this->user['username'];
	}

	/**
	 * Get the user's profile fields
	 *
	 * @return  Array
	 */
	public function get_profile_fields($field = null, $default = null)
	{
		if (empty($this->user))
		{
			return false;
		}

		if (isset($this->user['profile_fields']))
		{
			is_array($this->user['profile_fields']) or $this->user['profile_fields'] = @unserialize($this->user['profile_fields']);
		}
		else
		{
			$this->user['profile_fields'] = array();
		}

		return is_null($field) ? $this->user['profile_fields'] : \Arr::get($this->user['profile_fields'], $field, $default);
	}

	/**
	 * Extension of base driver method to default to user group instead of user id
	 */
	public function has_access($condition, $driver = null, $user = null)
	{
		if (is_null($user))
		{
			$groups = $this->get_groups();
			$user = reset($groups);
		}
		return parent::has_access($condition, $driver, $user);
	}

	/**
	 * Extension of base driver because this supports a guest login when switched on
	 */
	public function guest_login()
	{
		return \Config::get('siteauth.guest_login', true);
	}
}

// end of file siteauth.php
