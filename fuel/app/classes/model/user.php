<?php

class Model_User extends Auth\Model\Auth_User
{
	protected static $_table_name = 'users';
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'group_id',
		'email',
		'last_login',
		'previous_login',
		'login_hash',
		'user_id',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'group' => array(
			'model_to' => 'Model\\Auth_Group',
			'key_from' => 'group_id',
			'key_to'   => 'id',
			'cascade_delete' => false,
		),
	);

	protected static $_has_one = array(
		'admin' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Admin',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		)
	);

	protected static $_has_many = array(
		'providers' => array(
			'key_from' => 'id',
			'model_to' => 'Model_User_Provider',
			'key_to' => 'parent_id',
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
		'quest_participations' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Participant',
			'key_to' => 'user_id',
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
		'invites' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Invite_Email',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'notices' => array(
			'key_from' => 'id',
			'model_to' => 'Model_User_Notice',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'metadata' => array(
			'model_to' => 'Model_User_Metadata',
			'key_from' => 'id',
			'key_to'   => 'user_id',
			'cascade_delete' => true,
		),
		'userpermission' => array(
			'model_to' => 'Model\\Auth_Userpermission',
			'key_from' => 'id',
			'key_to'   => 'user_id',
			'cascade_delete' => false,
		),
	);

	protected static $_eav = array(
		'metadata' => array(
			'attribute' => 'key',
			'value' => 'value',
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

	public static function _init()
	{
		// throw new Exception("Error Processing Request", 1);
	}

	public function _event_after_insert()
	{
		$this->_update_friendships();
	}

	public function _event_before_save()
	{
		// if (isset($this->fullname) and $this->is_changed('fullname'))
		// {
		// 	$this->_update_friendships();
		// }
	}

	private function _update_friendships()
	{
		$friendships = Model_Friend::query()->where('friend_id', $this->id)->get();

		foreach ($friendships as $friendship)
		{
			$friendship->friend_name       = $this->display_name();
			$friendship->friend_registered = '1';
			$friendship->save();
		}
	}



	/**
	 * User's display name
	 */
	public function display_name()
	{
		// user metadata
		if (isset($this->fullname) and ! empty($this->fullname))
		{
			return $this->fullname;
		}

		// facebook metadata
		if ($provider = $this->get_provider('facebook') and ! empty($provider->fullname))
		{
			return $provider->fullname;
		}

		// username
		if ($username = $this->username and ! empty($username))
		{
			return $username;
		}
		
		// fallback to email
		return $this->email;
	}

	/**
	 * undefined_method
	 */
	public function is_admin()
	{
		return isset($this->admin->id);
	}

	/**
	 * undefined_method
	 */
	public function admin_url($action = 'view')
	{
		return "admin/accounts/{$action}/{$this->id}";
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
	 * Does the user have a password set
	 */
	public function has_password()
	{
		return ! empty($this->password);
	}



	// /**
	//  * undefined_method
	//  */
	// public function metadata($key, $value = null)
	// {
	// 	if (! $key or empty($key))
	// 	{
	// 		throw new Exception("undefined metadata key '{$key}'");
	// 	}

	// 	if (! $value)
	// 	{
	// 		return $this->$key ?: false;
	// 	}

	// 	if (isset($this->$key))
	// 	{
	// 		return $this->$key = $value and $this->save();
	// 	}

	// 	$this->metadata[] = \Auth\Model\Auth_Metadata::forge([
	// 		'parent_id' => $this->get_provider('facebook')->id,
	// 		'key'       => $key,
	// 		'value'     => $value,
	// 		'user_id'   => $this->id,
	// 	])->save();
	// }



	/**
	 * Avatars
	 */
	protected $avatar_sizes = array('200' => '200', '32' => '32');

	public function add_avatar($file)
	{
		$connection = Service_Cloudfiles::get_connection();

		foreach ($this->avatar_sizes as $width => $height)
		{
			$tmp_path        = $file['saved_to'] . $file['saved_as'];
			$file_name       = pathinfo($tmp_path, PATHINFO_FILENAME);
			$new_file_name   = "{$file_name}.png";
			$tmp_resize_path = APPPATH . "tmp/{$width}x{$height}_{$new_file_name}";

			Image::load($file['saved_to'] . $file['saved_as'])
				->crop_resize($width, $height)
				->save($tmp_resize_path);

			$container = $connection->get_container("avatar_{$width}x{$height}");
			$image     = $container->create_object($new_file_name);
			$image->load_from_filename($tmp_resize_path);

			$avatar = new Model_User_Avatar;
			$avatar->user_id              = $this->id;
			$avatar->name                 = $image->name;
			$avatar->public_uri           = $image->public_uri();
			$avatar->public_ssl_uri       = $image->public_ssl_uri();
			$avatar->public_streaming_uri = $image->public_streaming_uri();
			$avatar->width                = $width;
			$avatar->height               = $height;
			$avatar->content_length       = $image->content_length;
			$avatar->save();

			File::delete($tmp_resize_path);
		}

		File::delete($tmp_path);
	}

	/**
	 * undefined_method
	 */
	public function delete_avatars()
	{
		$connection = Service_Cloudfiles::get_connection();

		foreach ($this->get_avatars() as $avatar)
		{
			$container = $connection->get_container($avatar->container_name());
			$container->delete_object($avatar->name);
			$avatar->delete();
		}
	}

	/**
	 * undefined_method
	 */
	public function has_avatars()
	{
		return Model_User_Avatar::query()->where('user_id', $this->id)->count() > 0;
	}

	/**
	 * undefined_method
	 */
	public function get_avatar($width = 32, $height = 32)
	{
		return Model_User_Avatar::query()
			->where('user_id', $this->id)
			->where('width', $width)
			->where('height', $height)
			->get_one();
	}

	/**
	 * undefined_method
	 */
	public function get_avatars()
	{
		return Model_User_Avatar::query()->where('user_id', $this->id)->get();
	}

	/**
	 * undefined_method
	 */
	public function get_avatar_uri($width = 32, $height = 32)
	{
		if ($avatar = $this->get_avatar($width, $height))
		{
			return $avatar->public_uri;
		}

		if ($provider = $this->get_provider('facebook'))
		{
			return "https://graph.facebook.com/{$provider->uid}/picture?width={$width}&height={$height}";
		}

		$this->default_avatar_uri($width, $height);
	}

	/**
	 * undefined_method
	 */
	public function default_avatar_uri($width = 32, $height = 32)
	{
		return Uri::create("assets/img/avatar/default_{$width}x{$height}.png");
	}



	/**
	 * Notices
	 */
	public function has_seen_notice($type)
	{
		return Model_User_Notice::query()->where('user_id', $this->id)->where('type', $type)->count() > 0;
	}

	public function mark_notice_seen($type)
	{
		if ($this->has_seen_notice($type))
		{
			return true;
		}

		$notice = new Model_User_Notice;
		$notice->user_id = $this->id;
		$notice->type    = $type;
		return $notice->save();
	}



	/**
	 * Notifications
	 */
	public function set_notification($type, $send)
	{
		if (! $notification = $this->get_notification($type))
		{
			$notification = new Model_User_Notification;
			$notification->user_id = $this->id;
			$notification->type    = $type;
		}

		$notification->send = ($send ? '1' : '0');
		return $notification->save();
	}

	public function get_notification($type)
	{
		return Model_User_Notification::query()->where('user_id', $this->id)->where('type', $type)->get_one();
	}

	public function receives_notification($type)
	{
		$notification = Model_User_Notification::query()->where('user_id', $this->id)->get_one();

		return (! isset($notification) or $notification->send !== '0');
	}



	/**
	 * Get all user quests
	 */
	public function get_quests()
	{
		return Model_Quest::get_user_quests($this->id);
	}

	/**
	 *
	 */
	public function get_public_quests()
	{
		return Model_Quest::query()->where('user_id', $this->id)->where('is_public', 1)->order_by('name', 'asc')->get();
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

		if (! $quest->belongs_to_user($this))
		{
			return false;
		}

		return $quest->remove();
	}



	/**
	 * undefined_method
	 */
	public function has_friends()
	{
		return $this->total_friends() > 0;
	}

	/**
	 * undefined_method
	 */
	public function total_friends()
	{
		return Model_Friend::query()
			->where('user_id', $this->id)
			->where('friend_registered', '1')
			->where('hidden', '0')
			->count();
	}

	/**
	 * undefined_method
	 */
	public function get_friendships()
	{
		return Model_Friend::query()
			->where('user_id', $this->id)
			->where('friend_registered', '1')
			->where('hidden', '0')
			->order_by('friend_name', 'asc')
			->get();
	}

	/**
	 * undefined_method
	 */
	public function get_friends()
	{
		$users = array();
		foreach ($this->get_friendships() as $friendship)
		{
			$users[] = $friendship->get_friend();
		}
		return $users;
	}

	/**
	 * undefined_method
	 */
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

	/**
	 * undefined_method
	 */
	public function get_friend_ids()
	{
		$ids = array();
		foreach ($this->get_friendships() as $friendship)
		{
			array_push($ids, $friendship->friend_id);
		}
		return $ids;
	}

	/**
	 * undefined_method
	 */
	public function get_friend_ids_csv()
	{
		return join(',', $this->get_friend_ids());
	}

	/**
	 * undefined_method
	 */
	public function get_friendship_by_id($id)
	{
		return Model_Friend::query()->where('user_id', $this->id)->where('id', $id)->get_one();
	}

	/**
	 * undefined_method
	 */
	public function get_friendship_by_user_id($friend_id)
	{
		return Model_Friend::query()->where('user_id', $this->id)->where('friend_id', $friend_id)->get_one();
	}

	/**
	 * undefined_method
	 */
	public function get_friend_by_id($friend_id)
	{
		return ($friendship = $this->get_friendship_by_user_id($friend_id)) ? $friendship->friend : null;
	}

	/**
	 * undefined_method
	 */
	public function get_friend_profile_url($friend_id)
	{
		$friendship = $this->get_friendship_by_user_id($friend_id);
		return isset($friendship) ? $friendship->profile_url() : false;
	}

	/**
	 * undefined_method
	 */
	public function is_friend($friend_id)
	{
		return Model_Friend::query()->where('user_id', $this->id)->where('friend_id', $friend_id)->count() > 0;
	}

	/**
	 * undefined_method
	 */
	public function add_friend(Model_User $user)
	{
		if ($friend = $this->get_friend_by_id($user->id))
		{
			return null;
		}

		$self = new Model_Friend;
		$self->user_id           = $this->id;
		$self->friend_id         = $user->id;
		$self->friend_name       = $user->display_name();
		$self->friend_email      = $user->email;
		$self->hidden            = '0';
		$self->friend_registered = '1';

		$friend = new Model_Friend;
		$friend->user_id           = $user->id;
		$friend->friend_id         = $this->id;
		$friend->friend_name       = $this->display_name();
		$friend->friend_email      = $this->email;
		$friend->hidden            = '0';
		$friend->friend_registered = '1';

		return ($self->save() and $friend->save()) ? $friend : null;
	}


	/**
	 * undefined_method
	 */
	public function update_facebook_friends()
	{
		$new_friends = array();
		$provider    = $this->get_provider('facebook');

		foreach ($provider->fb_get_app_friends() as $facebook_friend)
		{
			if ($user = $facebook_friend->get_user() and ! $this->is_friend($user->id))
			{	
				if ($new_friend = $this->add_friend($user))
				{
					array_push($new_friends, $new_friend);
				}
			}
		}

		$provider->fb_friends_last_updated = time();

		return $new_friends;
	}

	/**
	 * undefined_method
	 */
	public function get_friends_quests()
	{
		if (! $this->has_friends()) return array();

		$result = DB::select()
			->from(Model_Quest::table())
			->where('user_id', 'in', $this->get_friend_ids())
			->as_object('Model_Quest')
			->execute();

		$friends_quests = array();

		foreach ($result->as_array() as $quest)
		{
			if ($quest->is_public() or $quest->is_participant($this->id))
			{
				array_push($friends_quests, $quest);
			}
		}

		return $friends_quests;
	}

	/**
	 * undefined_method
	 */
	public function get_friends_upcoming_quests()
	{
		if (! $this->has_friends()) return array();

		$result = DB::select()
			->from(Model_Quest::table())
			->where('user_id', 'in', $this->get_friend_ids())
			->where('purchase_by', '>', time())
			->where('is_public', '1')
			->order_by('purchase_by', 'asc')
			->as_object('Model_Quest')
			->execute();

		return $result->as_array();
	}

	/**
	 * undefined_method
	 */
	public function add_invitations_as_friends()
	{
		$invites = Model_Invite_Email::get_by_email($this->email);

		foreach ($invites as $invite)
		{
			if (! $this->is_friend($invite->user->id))
			{
				$this->add_friend($invite->user);
			}
		}
	}






	/**
	 * undefined_method
	 */
	public function get_provider($provider)
	{
		return Model_User_Provider::get_by_user_and_provider($this->id, $provider);
	}

	public function is_authenticated_with($provider)
	{
		$auth = Model_User_Provider::get_by_user_and_provider($this->id, $provider);
		return isset($auth->id);
	}

	public function link_provider(array $data)
	{
		if ($this->is_authenticated_with($data['provider']))
		{
			throw new Exception("User id:{$this->iid} already linked with provider '{$provider}");
		}

		$provider = Model_User_Provider::forge($data);
		
		$provider->save() and $this->update_facebook_friends();

		return $provider ?: false;
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

	/**
	 * undefined_method
	 */
	public function remove()
	{
		return $this->delete();
	}



	/**
	 *
	 */
	public static function create_user($info)
	{
		$user = static::forge($info);
		return $user->save() ? $user : false;
	}

	/**
	 * undefined_method
	 */
	public static function get_by_id($id)
	{
		return static::query()->where('id', $id)->get_one();
	}

	/**
	 * undefined_method
	 */
	public static function get_by_email($email)
	{
		return static::query()->where('email', $email)->get_one();
	}

	/**
	 * undefined_method
	 */
	public static function get_by_rest_code($reset_code)
	{
		return static::query()->where('reset_code', $reset_code)->get_one();
	}

}
