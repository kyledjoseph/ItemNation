<?php

class Controller_Admin_Deploy extends Controller_Admin
{
	public function before()
	{
		parent::before();

		$this->template->active_nav = 'accounts';
	}

	public function get_index()
	{
		$deployment_payloads = Deployment_Payload::get_recent();

		$this->template->body = View::forge('admin/deploy/index', [
			'deployment_payloads' => $deployment_payloads,
		]);
	}

	public function get_view($id)
	{
		if (! $deployment_payload = Deployment_Payload::get_by_id($id))
		{
			$this->redirect('admin/deploy');
		}

		$this->template->body = View::forge('admin/deploy/view', [
			'deployment_payload' => $deployment_payload,
		]);
	}

}