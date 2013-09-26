<?php

class Controller_Hook extends Controller_App
{
	public function post_deploy()
	{
		// ip validation
		$ip = Input::ip();
		if (! Deployment_Payload::valid_github_ip($ip))
		{
			throw new Exception("Invalid github hook ip address '{$ip}'", 1);
		}

		
		$payload_data = Input::post('payload');

		// log the post data
		$payload = new Deployment_Payload;
		$payload->data = $payload_data;
		$payload->save();

		// deploy to test and master


		return true;
	}
}