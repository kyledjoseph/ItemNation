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

	public function set_data($data)
	{
		if (! $this->valid_github_ip())
		{
			//throw new Exception("Invalid github hook ip address '{$payload->request_ip()}'", 1);
		}

		$this->_response = json_decode($data);
		$this->data = $data;
		$this->ip   = $this->request_ip();
		return $this->save();
	}

	public function log($type, $text)
	{
		$notice = new Deployment_Payload_Log;
		$notice->deployment_payload_id = $this->id;
		$notice->type = $type;
		$notice->text = $text;
		return $notice->save() ? $notice : false;
	}

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

	public function request_ip()
	{
		return Input::ip();
	}

	public function valid_github_ip()
	{
		// https://help.github.com/articles/what-ip-addresses-does-github-use-that-i-should-whitelist
		$valid_github_ips = array('204.232.175.64', '204.232.175.27', '192.30.252.0', '192.30.252.22');
		return in_array($this->request_ip(), $valid_github_ips);
	}

}