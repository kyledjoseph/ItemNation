<?php

class Controller_User_Auth extends Controller_App
{

	/**
	 * undefined_method
	 */
	public function get_login($provider = null)
	{
		if (! in_array($provider, ['facebook']))
		{
			$this->redirect('/');
		}

		$r = Input::get('r');

		$this->opauth = Auth_Social::forge([
			'provider' => $provider,
			// 'path'         => '/user/auth/login/',
			// 'callback_url' => Uri::create('authenticate'),
		]);
	}

	/**
	 * undefined_method
	 */
	public function get_authenticate($action = null)
	{
		// try
		// {
		// get the Opauth object
		$opauth = Auth_Social::forge(false);

		if (! $user = $opauth->login())
		{
			// check session for registration
			// Session::get('auth-strategy')
		}

		if (! Auth::check())
		{
			// throw new Exception("Error Processing Request", 1);
		}

		Response::redirect('');

		//$user->add_invitations_as_friends();


		if ($action and $action == 'bookmark')
		{
			Response::redirect('bookmark');
		}
		else
		{
			
		}
			
		// }

		// deal with Opauth exceptions
		// catch (OpauthException $e)
		// {
		// 	Response::redirect_back();
		// }

		// catch a user cancelling the authentication attempt
		// catch (OpauthCancelException $e)
		// {
		// 	$this->redirect('user/auth/login/facebook', 'error', '');
		// }
	}

	/**
	 * Logout
	 */
	public function get_logout()
	{
		$this->auth->logout() and $this->redirect('/');
	}

	/**
	 * undefined_method
	 */
	// public function get_info($status)
	// {
	// 	 // do we have an auth strategy to match?
	// 	if ($authentication = \Session::get('auth-strategy.authentication', array()))
	// 	{
	// 		// don't forget to pass false, we need an object instance, not a strategy call
	// 		$opauth = \Auth_Opauth::forge(false);

	// 		// call Opauth to link the provider login with the local user
	// 		$insert_id = $opauth->link_provider(array(
	// 			'parent_id' => $userid,
	// 			'provider' => $authentication['provider'],
	// 			'uid' => $authentication['uid'],
	// 			'access_token' => $authentication['access_token'],
	// 			'secret' => $authentication['secret'],
	// 			'refresh_token' => $authentication['refresh_token'],
	// 			'expires' => $authentication['expires'],
	// 			'created_at' => time(),
	// 		));

	// 		$authentication['provider']
	// 		$authentication['uid']
	// 		$authentication['access_token']
	// 		$authentication['secret']
	// 		$authentication['refresh_token']
	// 		$authentication['expires']
	// 	}
	// }
}