<?php

class Model_User_Metadata extends \Orm\Model
{
	
	protected static $_table_name = 'users_metadata';

	protected static $_properties = array(
		'id',
		'parent_id',
		'key',
		'value',
		'user_id'         => array(
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => false),
		),
		'created_at'      => array(
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => false),
		),
		'updated_at'      => array(
			'default' 	  => 0,
			'null'		  => false,
			'form'  	  => array('type' => false),
		),
	);

	/**
	 * @var array	defined observers
	 */
	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'property' => 'created_at',
			'mysql_timestamp' => false
		),
		'Orm\\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'property' => 'updated_at',
			'mysql_timestamp' => false
		),
		'Orm\\Observer_Typing' => array(
			'events' => array('after_load', 'before_save', 'after_save')
		),
		'Orm\\Observer_Self' => array(
			'events' => array('before_save'),
			'property' => 'user_id'
		),
	);

	/**
	 * @var array	belongs_to relationships
	 */
	protected static $_belongs_to = array(
		'user' => array(
			'model_to' => 'Model_User',
			'key_from' => 'user_id',
			'key_to'   => 'id',
		),
		'provider' => array(
			'model_to' => 'Model_User_Provider',
			'key_from' => 'parent_id',
			'key_to'   => 'id',
		),
	);

	/**
	 * init the class
	 */
   	public static function _init()
	{
		
	}

	/**
	 * before_insert observer event method
	 */
	public function _event_before_save()
	{
		// assign the user id that lasted updated this record
		
	}

	/**
	 * before_update observer event method
	 */
	// public function _event_before_update()
	// {
	// 	$this->_event_before_insert();
	// }
}
