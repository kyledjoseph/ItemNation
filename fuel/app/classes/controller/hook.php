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
				$repo = new PHPGit_Repository('/var/www/test');

				$payload->log('notice', 'Deploying to branch test.');

				if (! $repo->hasBranch('test'))
				{
					throw new Deployment_Exception("Branch 'test' does not exist on repository ");
				}

				// reset head
				$payload->log('input', '/var/www/test > git reset --hard');
				$payload->log('output', $repo->git('reset --hard 2>&1'));

				// git pull
				$payload->log('input', '/var/www/test > git pull origin test');
				$payload->log('output', $repo->git('pull origin test 2>&1'));
			}

			if ($payload->branch() == 'master')
			{
				$payload->log('notice', 'Deploying to branch master.');
			}
			$this->deploy_to_test();
		}

		catch (Exception $e)
		{
			$payload->log('exception', $e->getMessage());
			throw $e;
		}

		return true;
	}


	protected function deploy_to_test()
	{
		
	}
}