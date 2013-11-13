<?php

class Controller_User extends Controller_App
{

	/**
	 * Forgot password
	 */
	public function get_forgot()
	{
		$this->template->body = View::forge('user/forgot');
	}

	/**
	 * undefined_method
	 */
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
	
	/**
	 * undefined_method
	 */
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

	/**
	 * undefined_method
	 */
	public function post_account()
	{
		$this->require_auth();

		$post = $this->post_data('name', 'email');

		$this->user->fullname = $post->name;
		//$this->user->email        = $post->email;
		$this->user->save();

		$this->redirect('user/account', 'success', 'Account information updated');
	}

	/**
	 * undefined_method
	 */
	public function post_notifications()
	{
		$post = $this->post_data('digest');

		$send_digest = $post->digest == '1';
		$this->user->set_notification('digest', $send_digest);

		$this->redirect('user/account', 'success', 'Notification settings updated');
	}

	/**
	 * undefined_method
	 */
	public function get_avatar($action, $network)
	{
		if ($network == 'facebook')
		{
			$this->user->delete_avatars();
			$this->redirect('user/account', 'success', 'Account information updated');
		}

		$this->redirect('user/account', 'error', 'Invalid avatar type');
	}

	/**
	 * undefined_method
	 */
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