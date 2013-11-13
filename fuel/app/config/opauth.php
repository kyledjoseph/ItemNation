<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(

	/**
	 * undefined_method
	 */
	'path' => '/user/auth/login/',

	/**
	 * undefined_method
	 */
    'callback_url' => Uri::create('authenticate'),
    
    /**
     * undefined_method
     */
    'callback_transport' => 'get',

	/**
	 * link_multiple_providers
	 *
	 * Can multiple providers be attached to one user account
	 */
	'link_multiple_providers' => true,

	/**
	 * auto_registration
	 *
	 * If true, a login via a provider will automatically create a dummy
	 * local user account with a random password, if a nickname and an
	 * email address is present
	 */
	'auto_registration' => true,

	/**
	 * default_group
	 *
	 * Group id to be assigned to newly created users
	 */
	'default_group' => 1,

	/**
	 * debug
	 *
	 * Uncomment if you would like to view debug messages
	 */
	'debug' => false,

	/**
	 * A random string used for signing of auth response.
	 *
	 * You HAVE to set this value in your application config file!
	 */
	'security_salt' => 'Pe5zP44r982q860',

	/**
	 * Higher value, better security, slower hashing;
	 * Lower value, lower security, faster hashing.
	 */
	'security_iteration' => 300,

	/**
	 * Time limit for valid $auth response, starting from $auth response generation to validation.
	 */
	'security_timeout' => '2 minutes',

	/**
	 * Strategy
	 * Refer to individual strategy's documentation on configuration requirements.
	 */
	'Strategy' => array(
		'Facebook' => array(
			'app_id'     => '168874813262398',
			'app_secret' => '5aa0c283019c1f03cc5430559d80c0de',
			'scope'      => array('email', 'user_friends'),
		),
	 ),
);
