<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=localhost;dbname=shopgab',
			'username'   => 'root',
			'password'   => 'root',
		),
		'profiling'      => true,
	),
);
