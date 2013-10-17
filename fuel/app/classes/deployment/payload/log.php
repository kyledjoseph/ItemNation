<?php

class Deployment_Payload_Log extends \Orm\Model
{
	protected static $_table_name = 'deployment_payload_logs';
	protected static $_properties = array(
		'id',
		'deployment_payload_id',
		'type',
		'text',
		'created_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
	);

	public function date($format = "Y-m-d h:m:s")
	{
		return date($format, $this->created_at);
	}

	public function display_class_type()
	{
		// 'active'
		// 'success'
		// 'warning'
		// 'danger'

		//TODO move to config
		$classes = array(
			'input'     => 'warning',
			'output'    => 'success',
			'exception' => 'danger',
		);

		return array_key_exists($this->type, $classes) ? $classes[$this->type] : null;
	}
}