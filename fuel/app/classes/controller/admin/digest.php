<?php

class Controller_Admin_Digest extends Controller_Admin
{
	public function get_preview($date = '2013-09-21')
	{
		$users   = Model_User::query()->get();
		$digests = array();

		foreach ($users as $user)
		{
			$digest = new Notification_Digest($user, $date);
			
			if ($digest->has_notifications())
			{
				array_push($digests, $digest);
			}
		}

		$this->template->body = View::forge('admin/digest/preview', array(
			'digests'       => $digests,
			'total_digests' => count($digests),
			'date'          => $date,
		));

	}

	public function get_send($date  = '2013-09-21')
	{
		$users   = Model_User::query()->get();
		$digests = array();

		foreach ($users as $user)
		{
			$digest = new Notification_Digest($user, $date);
			
			if ($digest->has_notifications() and $digest->user->receives_notification('digest'))
			{
				$digest->send();
				array_push($digests, $digest);
			}
		}

		$total = count($digests);
		$this->redirect('admin/digest/preview', 'success', "$total digests sent for $date");
	}
}