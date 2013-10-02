<?php

class Controller_Admin_Analytics extends Controller_Admin
{
	public function before()
	{
		parent::before();

		$this->require_permission('view_analytics');


		$this->template->active_nav = 'analytics';
	}


	public function get_index()
	{
		$analytics = new Model_Analytics;

		$this->template->body = View::forge('admin/analytics/index', array('analytics' => $analytics));
	}

	public function get_report()
	{
		$start = Input::get('start');
		$until = Input::get('until');

		$analytics = new Model_Analytics($start, $until);

		$this->template->body = View::forge('admin/analytics/day', array('analytics' => $analytics));
	}

	public function get_csv($type)
	{
		$start = Input::get('start');
		$until = Input::get('until');

		$analytics = new Model_Analytics($start, $until);
		$content   = $analytics->get_csv($type);
		return $content;
		$response  = Response::forge($content, 200);

		$response->set_header('Content-Type', 'application/csv');
		$response->set_header('Content-Disposition', 'attachment; filename="shopgab-analytics.csv"');
		$response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
		$response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
		$response->set_header('Pragma', 'no-cache');
		$response->set_header('Content-Language', 'en');
		$response->set_header('Content-Type', 'text/html; charset=utf-8');

		return $response;
	}

	public function post_custom()
	{
		$start_month = Input::post('start_month');
		$start_day   = Input::post('start_day');
		$start_year  = Input::post('start_year');
		$until_month = Input::post('until_month');
		$until_day   = Input::post('until_day');
		$until_year  = Input::post('until_year');

		$start = "{$start_year}-{$start_month}-{$start_day}";
		$until = "{$until_year}-{$until_month}-{$until_day}";

		$query = '?start=' . $start;

		if ($start !== $until)
		{
			$query.= '&until=' . $until;
		}

		$this->redirect("admin/analytics/report" . $query);
	}
	
}