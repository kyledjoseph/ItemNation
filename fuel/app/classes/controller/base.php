<?php

class Controller_Base extends Controller_Template
{
	public function before()
	{
		parent::before();
	}

	/**
	 * undefined_method
	 */
	public function after($response)
	{
		if ($response === null)
		{
			return parent::after($this->template);
		}
		
		if (is_array($response))
		{
			return parent::after(json_encode($response));
		}

		return parent::after($response);
	}

	
	#
	#
	#
	public function get_data()
	{
		foreach (func_get_args() as $arg)
		{
			$data[$arg] = Input::get($arg);
		}
		return (object) $data;
	}


	#
	#
	#
	public function post_data()
	{
		foreach (func_get_args() as $arg)
		{
			$data[$arg] = Input::post($arg);
		}
		return (object) $data;
	}


	#
	#
	#
	public function http_method($is)
	{
		return (Input::method() === strtoupper($is));
	}


	#
	#
	#
	public function redirect($location, $type = null, $message = null)
	{
		if (!is_null($type) and !is_null($message))
		{
			Session::set_flash($type, $message);
		}

		Response::redirect($location);
	}
	
}