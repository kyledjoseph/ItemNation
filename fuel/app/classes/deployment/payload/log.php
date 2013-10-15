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
}