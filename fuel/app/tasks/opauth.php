<?php

namespace Fuel\Tasks;

class Opauth
{

	public function run()
	{
		$this->migrate_providers_data();
	}

	protected function migrate_providers_data()
	{
		$user_auths = \Model_User_Auth::query()->get();
		foreach ($user_auths as $user_auth)
		{
			// move properties from user_auths to users_providers
			$user_auth->user->username = $this->get_username_from_website_url($user_auth->profile_url);
			$user_auth->user->save();


			// move row from user_auths to users_providers
			list($provider_insert_id, $provider_rows_affected) = \DB::insert('users_providers')->set(array(
				'id'            => $user_auth->id,
				'parent_id'     => $user_auth->user_id,
				'provider'      => $user_auth->provider,
				'uid'           => $user_auth->provider_uid,
				// 'secret'        => '',
				// 'access_token'  => '',
				// 'expires'       => '',
				// 'refresh_token' => '',
				'user_id'       => $user_auth->user_id,
				'created_at'    => $user_auth->created_at,
				'updated_at'    => $user_auth->updated_at,
			))->execute();


			// move row from user_auths to users_metadata
			list($metadata_insert_id, $metadata_rows_affected) = \DB::insert('users_metadata')->set(array(
				'parent_id' => $provider_insert_id,
				'key'       => 'website',
				'value'     => $user_auth->website_url,
				'user_id'   => $user_auth->user_id,
			))->execute();

		}


		$users = \Model_User::query()->get();
		foreach ($users as $user)
		{

			// move user properties
			$user->group_id = '1';
			$user->user_id  = $user->id;
			$user->save();


			// move user properties to users_metadata
			$properties = array(
				'display_name' => 'fullname',
				//'avatar_type' => 'avatar_type',
				'reset_code' => 'reset_code',
				'reset_created_at' => 'reset_created_at',
				'fb_friends_last_updated' => 'fb_friends_last_updated',
			);

			foreach ($properties as $property => $new_key)
			{
				if (is_null($user->$property))
				{
					continue;
				}

				list($insert_id, $rows_affected) = \DB::insert('users_metadata')->set(array(
					'parent_id' => $user->id,
					'key'       => $new_key,
					'value'     => $user->$property,
					'user_id'   => $user->id,
				))->execute();

			}
		}


	}




	protected function get_username_from_website_url($url)
	{
		return str_replace('www.facebook.com/', '', $this->remove_protocol($url));
	}

	protected function remove_protocol($url)
	{
		foreach(array('http://', 'https://') as $protocol)
		{
			if (strpos($url, $protocol) === 0)
			{
				return str_replace($protocol, '', $url);
			}
		}
		return $url;
	}
}