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
			$this->add_modal(View::forge('user/modal/start_quest'));


			$this->template->body = View::forge('user/dashboard', array(
				'quests' => $this->user->get_open_quests(),
				'close_quest' => isset($close_quest) ? $close_quest : null,
			));


			// close quest window
			$expired_quest = $this->user->get_expired_quest();

			if ($expired_quest)
			{
				$this->template->body->close_quest = View::forge('user/close_quest', array(
					'quest' => $expired_quest,
				));
			}
		}

	}

	public function post_try()
	{
		$email = Input::post('email');

		Model_Try::add_address($email);

		$this->redirect('/');
	}
}