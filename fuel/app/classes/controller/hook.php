<?php

class Controller_Hook extends Controller_App
{
	// public function after($response)
	// {
	// 	return $response;
	// }

	public function post_deploy()
	{
		$payload_data = Input::post('payload');
		$payload = new Deployment_Payload;
		$payload->set_data(html_entity_decode($payload_data));

		try
		{
			if ($payload->branch() == 'test')
			{
				$payload->log('notice', 'Deploying to branch test.');

				$repo = new PHPGit_Repository('/var/www/shopgab');

				if (! $repo->hasBranch('test'))
				{
					throw new Deployment_Exception("Branch 'test' does not exist on repository ");
				}

				$payload->log('input', 'pull origin test');
				$output = $repo->git('pull origin test');
				$payload->log('output', $output);
			}

			if ($payload->branch() == 'master')
			{
				$payload->log('notice', 'Deploying to branch master.');
			}
		}

		catch (Exception $e)
		{
			$payload->log('exception', $e->getMessage());
			throw $e;
		}

		
			

		return true;
	}
}