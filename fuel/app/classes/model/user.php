<?php

class Model_User extends \Orm\Model
{
	protected static $_table_name = 'users';
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'group',
		'email',
		'display_name',
		'last_login',
		'login_hash',
		'profile_fields',
		'reset_code',
		'reset_created_at',
		'created_at',
		'updated_at',
	);

	protected static $_has_many = array(
		'authentications' => array(
			'key_from' => 'id',
			'model_to' => 'Model_User_Auth',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'quest_products' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Product',
			'key_to' => 'added_by',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'quest_product_comments' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Product_Comment',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'quest_product_votes' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Product_Vote',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'quests' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'friends' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Friend',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'friends_of' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Friend',
			'key_to' => 'friend_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
		'Orm\\Observer_Self' => array(
			'events' => array('before_save', 'after_insert')
		),
	);

	public function _event_after_insert()
	{
		$this->_update_friendships();
	}

	public function _event_before_save()
	{
		if ($this->is_changed('display_name'))
		{
			$this->_update_friendships();
		}
	}

	private function _update_friendships()
	{
		$friendships = Model_Friend::query()
			->where('friend_id', $this->id)
			->get();

		foreach ($friendships as $friendship)
		{
			$friendship->friend_name       = $this->display_name;
			$friendship->friend_registered = '1';
			$friendship->save();
		}
	}


	/**
	 * User's display name
	 */
	public function display_name()
	{

		return (isset($this->display_name) and ! empty($this->display_name)) ? $this->display_name : $this->email;
	}

	/**
	 * User's name
	 */
	// public function name()
	// {
	// 	return (! empty($this->first_name) and ! empty($this->last_name))
	// 		? "{$this->first_name} {$this->last_name}"
	// 		: null;
	// }

	/**
	 * Get user profile picture
	 */
	public function profile_pic($width = 32, $height = 32)
	{
		$fb_auth = $this->user_authentication('facebook');

		if (isset($fb_auth->id))
		{
			return "https://graph.facebook.com/{$fb_auth->provider_uid}/picture?width={$width}&height={$height}";
		}

		return '/assets/img/default/user/' . $width . 'x' . $height . '.png';
	}

	/**
	 * Does the user have a password set
	 */
	public function has_password()
	{
		return count($this->password) > 0;
	}

	/**
	 * User registration date
	 */
	public function member_since($format = "d M Y")
	{
		return date($format, $this->created_at);
	}

	/**
	 * Users last login
	 */
	public function last_login($format = "d M Y")
	{
		return date($format, $this->last_login);
	}

	/**
	 * Get all user quests
	 */
	public function get_quests()
	{
		return Model_Quest::get_user_quests($this->id);
	}

	/**
	 * Get a users quest by quest_url
	 */
	public function get_quest($quest_url)
	{
		return Model_Quest::get_user_quest($this->id, $quest_url);
	}

	/**
	 * Create a new quest
	 */
	public function create_quest($name, $description, $purchase_within)
	{
		return Model_Quest::create_quest($this->id, $name, $description, $purchase_within);
	}

	/**
	 * 
	 */
	public function select_quest()
	{
		$options = array();
		foreach ($this->get_quests() as $quest)
		{
			$options[$quest->url] = $quest->name();
		}
		return empty($options) ? array('none' => 'No Quests Available') : $options;
	}

	/**
	 * 
	 */
	public function remove_quest($quest_id)
	{
		$quest = Model_Quest::get_by_id($quest_id);

		if (! $quest->belongs_to_user($this->id))
		{
			return false;
		}

		return $quest->remove();
	}








	//TMP
	public function get_all_users()
	{
		return Model_User::query()->order_by('display_name', 'asc')->get();
	}
	public function select_all_users()
	{
		$options = array();
		$options['select'] = 'Select';
		foreach ($this->get_friends() as $friend)
		{
			$options[$friend->id] = $friend->display_name();
		}
		return empty($options) ? array('none' => 'No Friends Available') : $options;
	}



	/**
	 * undocumented class variable
	 */
	public function get_friendships()
	{
		return Model_Friend::query()
			->where('user_id', $this->id)
			->where('friend_registered', '1')
			->get();
	}

	public function get_friends()
	{
		$users = array();
		foreach ($this->get_friendships() as $friendship)
		{
			$users[] = $friendship->get_friend();
		}
		return $users;
	}

	public function select_friends()
	{
		$options = array();
		$options['select'] = 'Select';
		foreach ($this->get_friends() as $friend)
		{
			$options[$friend->id] = $friend->display_name();
		}
		return (count($options) < 1) ? array('none' => 'No Friends Available') : $options;
	}

	public function get_friend_ids()
	{
		$ids = array();
		foreach ($this->get_friendships() as $friendship)
		{
			array_push($ids, $friendship->friend_id);
		}
		return $ids;
	}

	public function get_friend_ids_csv()
	{
		return join(',', $this->get_friend_ids());
	}

	public function get_friendship_by_id($friend_id)
	{
		return Model_Friend::query()->where('user_id', $this->id)->where('friend_id', $friend_id)->get_one();
	}

	public function get_friend_by_id($friend_id)
	{
		$friendship = $this->get_friendship_by_id($friend_id);
		return isset($friendship) ? $friendship->friend : null;
	}

	public function is_friend($friend_id)
	{
		$count = Model_Friend::query()->where('user_id', $this->id)->where('friend_id', $friend_id)->count();
		return $count > 0;
	}

	public function add_friend($user)
	{
		if ($this->is_friend($user->id))
		{
			throw new Exception("user '{$this->id}' is already friends with '{$user_id}'");
		}

		$self = new Model_Friend;
		$self->user_id           = $this->id;
		$self->friend_id         = $user->id;
		$self->friend_name       = $user->display_name;
		$self->friend_registered = '1';

		$friend = new Model_Friend;
		$friend->user_id           = $user->id;
		$friend->friend_id         = $this->id;
		$friend->friend_name       = $this->display_name;
		$friend->friend_registered = '1';

		return $self->save() and $friend->save();
	}



	public function get_facebook_friends()
	{
		$auth       = Auth::instance();
		$hybridauth = $auth->hybridauth_instance();
		$adapter    = $hybridauth->authenticate('facebook');
		// return $adapter->getUserContacts();

		try
		{ 
			$response = $adapter->api()->api('/me/friends'); 
		}
		catch (FacebookApiException $e)
		{
			throw new Exception( "User contacts request failed! {$this->providerId} returned an error: $e" );
		} 
 
		if (! $response || ! count( $response["data"]))
		{
			return array();
		}

		$contacts = array();
 
		foreach ($response["data"] as $info)
		{
			$contacts[] = new Model_Facebook_Friend($info);
		}

		return $contacts;


	}

	public function get_registered_facebook_friends()
	{
		$auth       = Auth::instance();
		$hybridauth = $auth->hybridauth_instance();
		$adapter    = $hybridauth->authenticate('facebook');
		$fb_uid     = $this->user_authentication('facebook')->provider_uid;
		// return $adapter->getUserContacts();

		try
		{ 
			$query = urlencode("SELECT uid, name FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = {$fb_uid}) AND is_app_user = 1");
			$response = $adapter->api()->api('fql?q='.$query); 
		}
		catch (FacebookApiException $e)
		{
			throw new Exception( "User contacts request failed! {$fb_uid} returned an error: $e" );
		}

		if (! $response || ! count( $response["data"]))
		{
			return array();
		}

		$contacts = array();
 
		foreach ($response["data"] as $info)
		{
			$contacts[] = new Model_Facebook_Friend($info);
		}

		return $contacts;

		// foreach ($this->get_facebook_friends() as $friend)
		// {
		// 	if ($friend->is_registered())
		// 	{
		// 		$friends[] = $friend;
		// 	}
		// }
		// return ! empty($friends) ? $friends : array();
	}


	public function get_friends_quests()
	{
		$result = DB::select()
			->from(Model_Quest::table())
			->where('user_id', 'in', $this->get_friend_ids())
			->as_object('Model_Quest')
			->execute();

		return $result->as_array();
	}





	/**
	 * Authenticate a user with a provider
	 */
	public function authenticate_with($user_info)
	{
		$user_auth = Model_User_Auth::create_user_auth($this, $user_info);
	}

	/**
	 * Get all user authentications
	 */
	public function user_authentications()
	{
		return $this->authentications;
	}

	/**
	 * Get the user authentication for a specific provider
	 */
	public function user_authentication($provider)
	{
		return Model_User_Auth::get_by_user_and_provider($this->id, $provider);
	}

	/**
	 * Is user authenticated with auth provider
	 */
	public function is_authenticated_with($provider)
	{
		$auth = Model_User_Auth::get_by_user_and_provider($this->id, $provider);
		return isset($auth->id);
	}



	/**
	 * Generate a new password reset
	 */
	public function generate_reset_code()
	{
		$unique = false;
		while (! $unique)
		{
			$rand   = md5(uniqid(rand(), true));
			$total  = static::query()->where('reset_code', $rand)->count();
			$unique = $total == 0;
		}

		$this->reset_code = $rand;
		$this->reset_created_at = time();
		$this->save();
	}

	/**
	 * Validate a password reset code
	 */
	public function is_reset_code_valid()
	{
		$code    = $this->reset_code;
		$time    = $this->reset_created_at;
		$timeout = $time + (60 * 60 * 24 * 7); // 7 days

		if (! isset($code) or empty($code))
		{
			return false;
		}

		// reset expired
		if (! isset($time) or $timeout < time())
		{
			$this->empty_reset_code();
			return false;
		}

		return true;
	}

	/**
	 * Empty reset code
	 */
	public function empty_reset_code()
	{
		$this->reset_code = null;
		$this->reset_created_at = null;
		$this->save();
	}


	public function remove()
	{
		return $this->delete();
	}





	/**
	 * 
	 */
	public static function create_user($email, $password, $display_name = null)
	{
		$user = static::forge([
			'email'        => $email,
			'password'     => $password,
			'display_name' => $display_name,
		]);
		return $user->save() ? $user : false;
	}

	public static function get_by_id($id)
	{
		return static::query()->where('id', $id)->get_one();
	}

	public static function get_by_email($email)
	{
		return static::query()->where('email', $email)->get_one();
	}

	public static function get_by_rest_code($reset_code)
	{
		return static::query()->where('reset_code', $reset_code)->get_one();
	}

}