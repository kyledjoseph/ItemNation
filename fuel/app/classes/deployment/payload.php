<?php

class Deployment_Payload extends \Orm\Model
{
	protected static $_table_name = 'deployment_payloads';
	protected static $_properties = array(
		'id',
		'data',
		'ip',
		'created_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
	);


	protected $_response = null;


	public function branch()
	{
		return $this->ref_segment(3);
	}

	public function ref_segment($number)
	{
		return $this->ref_segments()[$number - 1];
	}

	public function ref_segments()
	{
		return explode('/', $this->_response->ref);
	}

	public function pretty_data()
	{
		return stripslashes(json_encode(json_decode($this->data), JSON_PRETTY_PRINT));
	}

	public function date($format = "r")
	{
		return date($format, $this->created_at);
	}

	public function ago($period = 'year')
	{
		return Date::time_ago($this->created_at, null, $period);
	}

	public function request_ip()
	{
		return Input::ip();
	}

	public function valid_github_ip()
	{
		// https://help.github.com/articles/what-ip-addresses-does-github-use-that-i-should-whitelist
		// $valid_github_ips = array('204.232.175.64', '204.232.175.27', '192.30.252.0', '192.30.252.22');
		// return in_array($this->request_ip(), $valid_github_ips);

		return true;
	}



	/**
	 *
	 */
	public function log($type, $text)
	{
		$notice = new Deployment_Payload_Log;
		$notice->deployment_payload_id = $this->id;
		$notice->type = $type;
		$notice->text = $text;
		return $notice->save() ? $notice : false;
	}

	public function get_logs()
	{
		return Deployment_Payload_Log::query()->where('deployment_payload_id', $this->id)->order_by('created_at', 'asc')->get();
	}



	public static function new_request($data)
	{
		$payload = new static();
		if (! $payload->valid_github_ip())
		{
			throw new Exception("Invalid github hook ip address '{$payload->request_ip()}'", 1);
		}

		$payload->_response = json_decode($data);
		$payload->data      = $data;
		$payload->ip        = $payload->request_ip();

		return $payload->save() ? $payload : false;
	}

	public static function get_recent($limit = 30)
	{
		return static::query()->order_by('created_at', 'desc')->limit($limit)->get();
	}

	public static function get_by_id($id)
	{
		return static::query()->where('id', $id)->get_one();
	}

}