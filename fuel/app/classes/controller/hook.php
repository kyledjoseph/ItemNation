<?php

class Controller_Hook extends Controller_App
{
	// public function after($response)
	// {
	// 	return $response;
	// }

	public function post_deploy()
	{
		$payload_data = Input::post('payload', false);
		$payload      = Deployment_Payload::new_request(html_entity_decode($payload_data));
		$deployment   = new Deployment;

		try
		{
			if ($payload->branch() == 'test')
			{
				$path = '/var/www/test';
				$repo = new PHPGit_Repository($path);

				$payload->log('notice', 'Deploying to branch test.');

				if (! $repo->hasBranch('test'))
				{
					throw new Deployment_Exception("Branch 'test' does not exist on repository ");
				}
				
				//TODO $deployment->repo->exec('fetch origin');

				// fetch
				$payload->log('input', "{$path} > git fetch origin");
				$payload->log('output', $repo->git('fetch origin 2>&1'));

				// reset head
				$payload->log('input', "{$path} > git reset --hard");
				$payload->log('output', $repo->git('reset --hard 2>&1'));

				// git pull
				$payload->log('input', "{$path} > git pull origin test");
				$payload->log('output', $repo->git('pull origin test 2>&1'));

				$deployment->notification->hipchat->message_room('Notifications', 'Deploy', 'This is just a test message!');
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


	protected function deploy_to_test()
	{
		
	}
}