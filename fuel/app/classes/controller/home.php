<?php

class Controller_Home extends Controller_App
{
	public function get_index()
	{
		if (! $this->user_logged_in())
		{
			Casset::css('landing/bootstrap.css');
			Casset::css('landing/bootstrap-responsive.css');
			Casset::css('landing/style.css');
			Casset::css('landing/parallax-slider.css');

			$modal = View::forge('user/modal/login');
			$modal.= View::forge('user/modal/register');

			return Response::forge(View::forge('landing/index', array(
				'modal' => $modal,
			)));
		}

		else
		{
			Casset::js('site/dashboard/tour.js');
			Casset::js('site/dashboard.js');
			$this->add_modal(View::forge('user/modal/start_quest'));

			$this->template->body = View::forge('user/dashboard', array(
				'quests' => $this->user->get_quests(),
			));
		}

	}

	public function post_try()
	{
		$email = Input::post('email');

		Model_Try::add_address($email);

		$this->redirect('/');
	}
}