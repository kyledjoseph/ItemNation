<?php

class Deployment_Payload extends \Orm\Model
{
	protected static $_table_name = 'deployment_payloads';
	protected static $_properties = array(
		'id',
		'data',
		'created_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
	);



	public static function valid_github_ip($ip)
	{
		$valid_github_ips = array('204.232.175.64', '204.232.175.27', '192.30.252.0', '192.30.252.22');
		return in_array($ip, $valid_github_ips);
	}
}