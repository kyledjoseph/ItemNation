<?php

class Controller_Base extends Controller_Hybrid
{
	public function before()
	{
		parent::before();
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
	public function redirect($location, $type = null, $message = null, $display = "")
        {
            /* AJAX check  */
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if($display)
                $response = array('status'=>$type, 'message'=>$message, 'data'=>array('redirect'=> $location, 'view' => $display->render()));
                else
                $response = array('status'=>$type, 'message'=>$message, 'data'=>array('redirect'=> $location, 'view' => ''));
                echo Format::forge($response)->to_json();
                die(0);
            }
            if (!is_null($type) and !is_null($message))
            {
                Session::set_flash($type, $message);
            }

            Response::redirect($location);
        }

}
