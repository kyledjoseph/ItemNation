<?php

class Controller_Debug extends Controller_App
{

	public function get_set()
	{
		if (! $user = Model_User::get_by_email('tmatthewsdev@gmail.com'))
		{
			throw new Exception("User");
		}

		if (! $provider = $user->get_provider('facebook'))
		{
			throw new Exception("Provider");
		}
		

		$provider->next = 'bar';
		$provider->save();

		return "test = " . $provider->next;

		return "resp " . $user->get_provider('facebook')->test; //, 'one');

	}

	public function get_fb()
	{
		if (! $user = Model_User::get_by_email('tmatthewsdev@gmail.com'))
		{
			throw new Exception("User");
		}

		if (! $provider = $user->get_provider('facebook'))
		{
			throw new Exception("Provider");
		}

		return $provider->fb_get_app_friends();

		// $facebook = new Facebook(array(
		// 	'appId'  => '168874813262398',
		// 	'secret' => '5aa0c283019c1f03cc5430559d80c0de',
		// ));

		// $facebook->setAccessToken($provider->access_token);

		// //$fb_uid = $facebook->getUser();
		// $query  = "SELECT uid, name FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = {$provider->uid}) AND is_app_user = 1";
		// $params = array(
		// 	'method' => 'fql.query',
		// 	'query'  => $query,
		// );

		// if (! $result = $facebook->api($params))
		// {
		// 	throw new Exception("Error Processing Request", 1);
		// }

		// $contacts = array();

		// foreach ($result as $info)
		// {
		// 	$contacts[] = new Model_Facebook_Friend($info);
		// }

		// return $contacts;

		// return array('result' => $result);
	}

	public function get_git()
	{
		echo shell_exec("cd /var/www/test && git pull origin test 2>&1");

		// $repo = new PHPGit_Repository('/var/www/test');

		// if (! $repo->hasBranch('test'))
		// {
		// 	throw new Deployment_Exception("Branch 'test' does not exist on repository ");
		// }

		// echo $repo->git('pull origin test');

		return true;
	}

	public function get_events()
	{
		$date     = '2013-09-21';
		$users    = Model_User::query()->get();
		$total = 0;

		foreach ($users as $user)
		{
			$message = new Notification_Digest($user, $date);
			
			if ($message->has_notifications())
			{
				
				echo var_export($message->get_quests());
				echo '<hr>';

				$total++;
			}
		}

		return 'total: ' . $total;
	}



	public function get_senddigest()
	{
		$date     = '2013-09-21';
		$users    = Model_User::query()->get();
		$total = 0;

		foreach ($users as $user)
		{
			$message = new Notification_Digest($user, $date);
			
			if ($message->has_notifications())
			{
				$message->send();
				$total++;
			}
		}

		return 'total: ' . $total;
	}










	public function get_send()
	{
		return base64_encode(file_get_contents(DOCROOT . 'assets/img/email/logo.png'));
	}


	public function get_cloud()
	{
		$username = 'itemnation';
		$api_key  = '750116f1c657bddb347b0a747ea5fd1d';

		# Authenticate to Cloud Files.  The default is to automatically try
		# to re-authenticate if an authentication token expires.
		#
		# NOTE: Some versions of cURL include an outdated certificate authority (CA)
		#       file.  This API ships with a newer version obtained directly from
		#       cURL's web site (http://curl.haxx.se).  To use the newer CA bundle,
		#       call the CF_Authentication instance's 'ssl_use_cabundle()' method.
		#
		$auth = new CF_Authentication($username, $api_key);
		# $auth->ssl_use_cabundle();  # bypass cURL's old CA bundle
		$auth->authenticate();

		
		# Establish a connection to the storage system
		#
		# NOTE: Some versions of cURL include an outdated certificate authority (CA)
		#       file.  This API ships with a newer version obtained directly from
		#       cURL's web site (http://curl.haxx.se).  To use the newer CA bundle,
		#       call the CF_Connection instance's 'ssl_use_cabundle()' method.
		#
		$conn = new CF_Connection($auth);
		# $conn->ssl_use_cabundle();  # bypass cURL's old CA bundle


		# Create a Container and make it public
		$images = $conn->create_container("avatar_32x32");
		$uri    = $images->make_public();

		$images = $conn->create_container("avatar_200x200");
		$uri    = $images->make_public();

		return $uri;

		# Get a remote Container and storage Object
		#
		$images = $conn->get_container("photos");
		$image  = $images->create_object("first_birthday.jpg");

		$image->load_from_filename("/home/user/photos/birthdays/birthday1.jpg");

		# Or... print out the Object's public URI
		#
		return $image->public_uri();
	}









	public function get_email()
	{
		$message = array(
			'html'       => 'hello world!',
			'subject'    => 'test message',
			'from_email' => 'test@itemnation.com',
			'from_name'  => 'itemnation.com',
			'to' => array(
				array('email'  => 'tmatthewsdev@gmail.com', 'name'   => 'Tyler Matthews',)
			),
		);

		$mandrill = new Mandrill('TuKwIsrSRAh7nwWiWVXZyQ');
		$mandrill->messages->send($message, true);

		return Response::forge('sent');
	}


	public function get_friends()
	{
		$hybridauth = $this->auth->hybridauth_instance();
		$adapter    = $hybridauth->authenticate('facebook');
		$friends    = $adapter->getUserContacts();

		echo '<pre>';
		echo var_dump($friends);
		echo '</pre>';

		return Response::forge();
	}

	public function get_curl()
	{
		$url           = 'http://img87.imageshack.us/img87/5673/rotatetrans.png';
		$path_parts    = pathinfo($url);
		$ext           = $path_parts['extension'];
		$supported_ext = array('jpg', 'jpeg', 'png', 'bmp');

		if (empty($ext) or ! in_array($ext, $supported_ext))
		{
			throw new Exception("Unknown or unsupported extension: '$ext' in product image url: '$url'");
		}
		
		// make request
		$curl = Request::forge($url, 'curl');
		$curl->execute();
		$image_data = $curl->response();

		$save_dir  = DOCROOT . '/assets/tmp/';
		$rand      = md5(uniqid(rand(), true));
		$orig_file = $rand . '.' . $ext;

		// 
		File::create($save_dir, $orig_file, $image_data);

		// 
		$image = Image::forge();
		$image->load("{$save_dir}{$orig_file}")
			->crop_resize(200, 200)
			->save(null, '_thumb');

		return Response::forge('done');
	}
}