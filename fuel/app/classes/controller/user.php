<?php

class Controller_User extends Controller_App
{
	
	/**
	 * User Login
	 */
	public function get_login()
	{
		$this->redirect('/');
		$this->template->body = View::forge('user/login');
	}

	public function post_login()
	{
		$post     = $this->post_data('email', 'password', 'remember', 'redirect');
		$remember = ($post->remember == 'true');
		$success  = $this->auth->login($post->email, $post->password);

		
		if (Input::is_ajax())
		{

			if (! $success)
			{
				return Response::forge(json_encode(array(
					'success' => false,
					'type'    => 'login_invalid',
					'field'    => '#error_login',
					'message' => 'Invalid email address or password',
				)));
			}

			return Response::forge(json_encode(array(
				'success'  => true,
				'type'     => 'login_success',
				'message'  => 'You are now logged in',
				'redirect' => $post->redirect,
			)));
		}
		else
		{
			if (! $success)
			{
				$this->redirect('/', 'error', 'Invalid email address or password');
			}
			else
			{
				$this->redirect('/', 'success', 'You are now logged in');
			}
		}

	}



	/**
	 * User authentication with Facebook, Twitter, and Google
	 */
	public function get_auth($provider)
	{
		$provider = strtolower($provider);
		$get      = $this->get_data('r');

		if (! in_array($provider, ['facebook', 'twitter', 'google']))
		{
			$this->redirect('user/login', 'info', 'Invalid service provider');
		}

		// try
		// {
		// 	$login_success = $this->auth->social_auth($provider);
		// }
		// catch (Exception $e)
		// {
		// 	switch ($e->getCode())
		// 	{
		// 		case 0 : $error = "Unspecified error."; break;
		// 		case 1 : $error = "Hybriauth configuration error."; break;
		// 		case 2 : $error = "Provider not properly configured."; break;
		// 		case 3 : $error = "Unknown or disabled provider."; break;
		// 		case 4 : $error = "Missing provider application credentials."; break;
		// 		case 5 : $error = "Authentication failed. The user has canceled the authentication or the provider refused the connection."; break;
		// 		case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again."; 
		// 			     $adapter->logout(); 
		// 			     break;
		// 		case 7 : $error = "User not connected to the provider."; 
		// 			     $adapter->logout(); 
		// 			     break;
		// 	}

		// 	// well, basically your should not display this to the end user, just give him a hint and move on..
		// 	$error = "<br /><br /><b>Original error message:</b> " . $e->getMessage(); 
		// 	$error.= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>"; 
		// 	return $error;
		// }

		
		if (! $this->auth->social_auth($provider))
		{
			throw new Exception("Could not log in social auth user to provider: '{$provider}'", 1);
			$this->redirect('/', 'error', 'An error has occurred');
		}

		

		if (! empty($get->r))
		{
			$this->redirect($get->r);
		}

		$this->redirect('/');

	}

	public function get_process()
	{
		Hybrid_Endpoint::process();
		$this->redirect('/');
	}



	/**
	 * User Registration
	 */
	public function get_register()
	{
		$this->redirect('/');
		$this->template->body = View::forge('user/register');
	}

	public function post_register()
	{
		if (! Input::is_ajax())
		{
			$this->redirect('/');
		}

		$post = $this->post_data('name', 'email', 'password', 'confirm_password', 'redirect');

		// name missing
		if (empty($post->name))
		{
			return Response::forge(json_encode(array(
				'success' => false,
				'type'    => 'name_missing',
				'field'   => '#error_name',
				'message' => 'Enter your name',
			)));
		}

		// email missing
		if (empty($post->email))
		{
			return Response::forge(json_encode(array(
				'success' => false,
				'type'    => 'email_missing',
				'field'   => '#error_email',
				'message' => 'Enter your email address',
			)));
		}

		// password match
		if (empty($post->password) or empty($post->confirm_password))
		{
			return Response::forge(json_encode(array(
				'success' => false,
				'type'    => 'password_match',
				'field'   => '#error_password',
				'message' => 'Enter and confirm your new password',
			)));
		}

		// password match
		if ($post->password !== $post->confirm_password)
		{
			return Response::forge(json_encode(array(
				'success' => false,
				'type'    => 'password_match',
				'field'   => '#error_password',
				'message' => 'Your password does not match the password confirmation',
			)));
		}

		// register user
		try
		{
			$user = $this->auth->create_user($post->email, $post->password);
		}
		catch (SiteUserUpdateException $e)
		{
			return Response::forge(json_encode(array(
				'success' => false,
				'type'    => 'email_registered',
				'field'   => '#error_email',
				'message' => $e->getMessage(),
			)));
		}
		
		if (! isset($user))
		{
			return Response::forge(json_encode(array(
				'success' => false,
				'type'    => 'register_error',
				'field'   => '#error_password',
				'message' => 'An error has occured',
			)));
		}

		// additional account info
		$user->display_name = $post->name;
		$user->save();

		// add past invitations as friends
		$user->add_invitations_as_friends();

		// log user in
		$this->auth->login($post->email, $post->password);

		// success
		return Response::forge(json_encode(array(
			'success'  => true,
			'type'     => 'login_success',
			'message'  => 'You are now logged in',
			'redirect' => $post->redirect,
		)));
	}



	/**
	 * Forgot password
	 */
	public function get_forgot()
	{
		$this->template->body = View::forge('user/forgot');
	}

	public function post_forgot()
	{
		$post = $this->post_data('email');
		$user = Model_User::get_by_email($post->email);
		
		if (! isset($user))
		{
			$this->redirect('forgot', 'error', 'That email address is not registered');
		}

		$user->generate_reset_code();
		
		Service_Email::send(array(
			'type'      => 'password_reset',
			'to_addr'   => $user->email,
			'from_name' => 'ShopGab',
			'from_addr' => 'info@shopgab.com',
			'subject'   => 'Reset your ShopGab password',
			'body'      => View::forge('emails/reset', array(
				'reset_code' => $user->reset_code,
			)),
		));

		$this->redirect('login', 'success', 'An email with instructions to reset your password has been sent to the address provided');
	}



	/**
	 * Reset password
	 */
	public function get_reset($reset_code)
	{
		$user = Model_User::get_by_rest_code($reset_code);

		if (! isset($user))
		{
			$this->redirect('forgot', 'error', 'Invalid password reset');
		}

		$this->template->body = View::forge('user/reset');
	}
	
	public function post_reset($reset_code)
	{
		$user = Model_User::get_by_rest_code($reset_code);

		if (! isset($user) or ! $user->is_reset_code_valid())
		{
			$this->redirect('forgot', 'error', 'Invalid password reset');
		}

		$post = $this->post_data('password', 'confirm_password');

		// password match
		if ($post->password !== $post->confirm_password)
		{
			$this->redirect('user/register', 'error', 'Your password does not match the password confirmation');
		}

		$this->auth->reset_password($user->email, $post->password);
		$user->empty_reset_code();
		$this->redirect('login', 'success', 'Your password has been changed.');
	}



	/**
	 * Logout
	 */
	public function get_logout()
	{
		$this->auth->logout();
		$this->redirect('/');
	}



	


	/**
	 * Verify email address
	 */
	public function get_verify($user_hash)
	{

	}




	/**
	 * 
	 */
	public function post_subscribe()
	{
		$post = $this->post_data('email');
		empty($post->email) and $this->redirect('', 'info', 'Enter you email address');

		TinyChimp::listSubscribe(array(
			'id'            => '046b807986',
			'email_address' => $post->email,
			'double_optin'  => true,
			'send_welcome'  => true
		));
	}


	/**
	 * 
	 */
	public function post_invite()
	{
		$post = $this->post_data('emails');
		empty($post->emails) and $this->redirect('', 'info', 'Enter you email address');

		$emails = explode(',', $post->emails);
		foreach ($emails as $email)
		{
			$email = trim($email);

			Service_Email::send(array(
				'type'      => 'in_invite',
				'to_addr'   => $email,
				'from_name' => 'ShopGab Invite',
				'from_addr' => 'invite@shopgab.com',
				'subject'   => 'You have been invited to ShopGab',
				'body'      => View::forge('emails/invite'),
			));
		}
		
		return Response::forge(json_encode($emails));
	}


	/**
	 * User Feedback
	 */
	public function post_feedback()
	{
		$name     = Input::post('name');
		$email    = Input::post('email');
		$referral = Input::post('referral');
		$content  = Input::post('content');

		Model_Feedback::create_feedback(array(
			'user_id'      => ($this->user_logged_in() ? $this->user->id : null),
			'user_email'   => $email,
			'referral_url' => $referral,
			'feedback'     => $content,
		));

		$this->redirect($referral, 'success', 'Thank you for your feedback!');
	}



	/**
	 * Edit account settings
	 */
	public function get_account()
	{
		$this->require_auth();

		$this->template->body = View::forge('user/account');
	}

	public function post_account()
	{
		$this->require_auth();

		$post = $this->post_data('name', 'email');

		$this->user->display_name = $post->name;
		//$this->user->email        = $post->email;
		$this->user->save();

		$this->redirect('user/account', 'success', 'Account information updated');
	}

	public function post_notifications()
	{
		$post = $this->post_data('digest');

		$send_digest = $post->digest == '1';
		$this->user->set_notification('digest', $send_digest);

		$this->redirect('user/account', 'success', 'Notification settings updated');
	}

	public function get_avatar($action, $network)
	{
		if ($network == 'facebook')
		{
			$this->user->set_avatar_type('facebook');
			$this->redirect('user/account', 'success', 'Account information updated');
		}

		$this->redirect('user/account', 'error', 'Invalid avatar type');
	}

	public function post_avatar()
	{
		$this->require_auth();

		Upload::process(array(
			'path' => APPPATH . 'tmp',
			'randomize' => true,
			'ext_whitelist' => array('jpg', 'jpeg', 'gif', 'png', 'bmp'),
		));

		if (Upload::is_valid())
		{
			Upload::save();

			if ($this->user->has_avatars())
			{
				$this->user->delete_avatars();
			}

			foreach (Upload::get_files() as $file)
			{
				// return Response::forge(var_export($file));
				// $file = array ( 'name' => 'computech.jpg', 'type' => 'image/jpeg', 'error' => false, 'size' => 550696, 'field' => 'avatar', 'file' => '/Applications/MAMP/tmp/php/phpum5qaT', 'errors' => array ( ), 'extension' => 'jpg', 'filename' => 'computech', 'mimetype' => 'image/jpeg', 'saved_to' => '/Users/tmatthews/Sites/shopgab/fuel/app/tmp/', 'saved_as' => 'b30b61736b21b15b59a78fe16742c0c4.jpg', )

				$this->user->add_avatar($file);
				break;
			}
		}

		foreach (Upload::get_errors() as $file)
		{
			// $file is an array with all file information,
			// $file['errors'] contains an array of all error occurred
			// each array element is an an array containing 'error' and 'message'
		}

		$this->redirect('user/account', 'success', 'Avatar Uploaded');
	}



	/**
	 * Change Password
	 */
	public function get_password()
	{
		$this->require_auth();

		$this->template->body = View::forge('user/password');
	}

	public function post_password()
	{
		$this->require_auth();


		$post = $this->post_data('current', 'new', 'confirm');

		if ($post->new !== $post->confirm)
		{
			$this->redirect('user/password', 'error', 'Your new password did not match the password confirmation');
		}

		// set initial user password (for account using social auth)
		if (! $this->user->has_password() and $this->auth->set_password($post->new))
		{
			$this->redirect('user/account', 'success', 'Password set');
		}

		if (! $this->auth->change_password($post->current, $post->new))
		{
			$this->redirect('user/password', 'error', 'Your current password is incorrect');
		}

		$this->redirect('user/account', 'success', 'Password changed');
	}
	
}