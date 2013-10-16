<?php

class Controller_Debug extends Controller_App
{

	public function get_git()
	{
		$path = '/Users/tmatthews/Sites/git';
		$php  = '/Applications/MAMP/bin/php/php5.4.10/bin/php';
		
		$repo = new PHPGit_Repository($path, true);

		echo shell_exec("cd $path && pwd && whoami && /usr/bin/git status 2>&1");

		// echo shell_exec("cd $path && $php oil r digest 2>&1");

		// if (! $repo->hasBranch('master'))
		// {
		// 	throw new Exception("repo has no branch master");
		// }

		// echo $repo->getCurrentBranch();

		//$repo->git('status');

		//echo exec('cd /' . $loc . ' && git status', $out);

		//echo var_export($out)

		// echo shell_exec("cd $path && git status 2>&1");

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

	public function get_fb()
	{
		$friends = $this->user->get_registered_facebook_friends();
		echo '<pre>';
		echo var_dump($friends[1]['data']);
		echo '</pre>';
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