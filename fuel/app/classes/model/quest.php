<?php

class Model_Quest extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'url',
		'user_id',
		'name',
		'description',
		'purchase_within',
		'purchase_by',
		'default_product_id',
		'is_public',
		'created_at',
		'updated_at',
	);


	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
	);

	protected static $_has_many = array(
		'quest_products' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Product',
			'key_to' => 'quest_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'participants' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Participant',
			'key_to' => 'quest_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'messages' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Message',
			'key_to' => 'quest_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
	);

	protected static $_observers = array(
		'Observer_Quest_Url' => array(
			'events' => array('before_insert'),
		),
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	protected $active_sort = 'recent';

	public function name()
	{
		return ! empty($this->name) ? $this->name : 'no name';
	}
	public function description()
	{
		return ! empty($this->description) ? $this->description : 'no description';
	}

	public function full_url($page = null)
	{
		return Uri::create($this->url($page));
	}

	public function url($page = null)
	{
		return '/quest/' . $this->url . (! empty($page) ? '/' . $page : null);
	}

	public function belongs_to_user($user_id)
	{
		return $user_id == $this->user_id;
	}
	
	public function date($format = "r")
	{
		return date($format, $this->created_at);
	}

	public function is_public()
	{
		return $this->is_public == '1';
	}

	public function is_private()
	{
		return $this->is_public == '0';
	}



	public function purchase_within_option()
	{
		return round(($this->purchase_within() / 7));
		//return ! empty($this->purchase_within) ? $this->purchase_within : '0';
	}

	public function purchase_within()
	{
		return ! empty($this->purchase_by)
		 ? round(($this->purchase_by - time()) / 86400)
		 : $this->purchase_within_value($this->purchase_within) / 86400;
	}

	public static function purchase_within_fields()
	{
		return array(
			'0' => 'Not Sure',
			'1' => '1 week',
			'2' => '2 weeks',
			'3' => '3 weeks',
			'4' => '1 month',
			//'5' => 'more than 1 month',
		);
	}

	public static function purchase_within_value($index)
	{
		$values = array(
			'0' => 0,
			'1' => 604800,
			'2' => 1209600,
			'3' => 1814400,
			'4' => 2419200,
			//'5' => 'more than 1 month',
		);

		return $values[$index];
	}

	public function set_purchase_within($purchase_within)
	{
		$this->purchase_within = $purchase_within;
		$this->purchase_by     = time() + $this->purchase_within_value($purchase_within);
	}





	public function default_thumb_url($width = 50, $height = 50)
	{
		if (isset($this->default_product_id))
		{
			$image = Model_Product_Image::query()->where('product_id', $this->default_product_id)->where('width', $width)->where('height', $height)->get_one();

			if (isset($image))
			{
				return $image->public_uri;
			}
		}

		foreach ($this->quest_products as $quest_product)
		{
			$image = $quest_product->product->image($width, $height);
			if (! empty($image->public_uri))
			{
				$this->default_product_id = $image->product_id;
				$this->save();
				return $image->public_uri;
			}
		}

		return "http://placehold.it/{$width}x{$height}/fff";
	}



	/**
	 * 
	 */
	public function sort_options()
	{
		return array(
			'recent'   => 'Recently Added',
			'likes'    => 'Most Liked',
			'dislikes' => 'Most Disliked',
			'updated'  => 'Recent Updates',
		);
	}

	public function is_sort_type($type)
	{
		return in_array($type, array_keys($this->sort_options()));
	}

	public function set_active_sort($type)
	{
		if (! $this->is_sort_type($type))
		{
			throw new Exception("Invalid Quest Products sort type '$type'", 1);
		}

		$this->active_sort = $type;
	}

	public function active_sort()
	{
		return $this->is_sort_type($this->active_sort) ? $this->active_sort : 'recent';
	}



	/**
	 * 
	 */
	public function get_quest_products()
	{
		return Model_Quest_Product::query()
			->where('quest_id', $this->id)
			->order_by('created_at', 'asc')->get();
	}

	/**
	 * 
	 */
	public function get_quest_products_sorted()
	{
		$sort  = $this->active_sort();
		$query = Model_Quest_Product::query()->where('quest_id', $this->id);

		if ($sort == 'recent')
		{
			$query->order_by('created_at', 'desc');
		}

		if ($sort == 'likes')
		{
			$query->order_by('total_likes', 'desc');
		}

		if ($sort == 'dislikes')
		{
			$query->order_by('total_dislikes', 'desc');
		}

		if ($sort == 'updated')
		{
			$query->order_by('updated_at', 'desc');
		}

		return $query->get();
	}

	/**
	 * 
	 */
	public function get_quest_product($quest_product_id)
	{
		return Model_Quest_Product::query()
			->where('quest_id', $this->id)
			->where('id', $quest_product_id)
			->order_by('created_at', 'asc')->get_one();
	}

	/**
	 * 
	 */
	public function add_product($product_id, $added_by = 0)
	{
		$added_by = ($added_by == 0) ? $this->user_id : $added_by;

		$quest_product = Model_Quest_Product::forge(array(
			'quest_id' => $this->id,
			'product_id' => $product_id,
			'added_by' => $added_by,
		));

		$notice = Model_Notification::new_product($this->user_id, $this, $quest_product->id);

		$this->add_participant($added_by);

		return $quest_product->save() ? $quest_product : null;
	}

	

	/**
	 * 
	 */
	public function new_message($user_id, $message)
	{
		$message = Model_Quest_Message::create_message($this->id, $user_id, $message);
		$this->add_participant($user_id);

		Model_Notification::new_message($user_id, $this, $message->id);

		return $message;
	}

	public function get_messages()
	{
		return Model_Quest_Message::query()->where('quest_id', $this->id)->order_by('created_at', 'asc')->get();
	}


	/**
	 * Quest Participants
	 */
	public function get_participants()
	{
		return Model_Quest_Participant::query()->where('quest_id', $this->id)->get();
	}

	public function get_participant($user_id)
	{
		return Model_Quest_Participant::query()->where('quest_id', $this->id)->where('user_id', $user_id)->get_one();
	}

	public function total_participants()
	{
		return Model_Quest_Participant::query()->where('quest_id', $this->id)->count();
	}

	public function is_participant($user_id)
	{
		return Model_Quest_Participant::query()->where('quest_id', $this->id)->where('user_id', $user_id)->count() > 0;
	}

	public function add_participant($user_id)
	{
		if ($user_id == $this->user_id)
		{
			return true;
		}
		if ($this->is_participant($user_id))
		{
			return true;
		}

		return Model_Quest_Participant::add_participant($this->id, $user_id);
	}



	/**
	 * Quest Notifications
	 */
	public function get_unseen_notifications()
	{
		return Model_Notification::query()->where('quest_id', $this->id)->where('seen_at', null)->get();
	}

	public function total_unseen_notifications()
	{
		return Model_Notification::query()->where('quest_id', $this->id)->where('seen_at', null)->count();
	}

	public function mark_notifications_seen()
	{
		foreach ($this->get_unseen_notifications() as $notification)
		{
			$notification->mark_seen();
		}
	}

	public function get_notifications_on_date($date)
	{
		list($y, $m, $d) = explode('-', $date);
		$start = mktime(0, 0, 0, $m, $d, $y);
		$end   = mktime(0, 0, 0, $m, $d+1, $y);
		return Model_Notification::query()->where('quest_id', $this->id)->where('created_at', '>=', $start)->where('created_at', '<', $end)->get();
	}




	public function remove()
	{
		// remove associated images

		return $this->delete();
	}


	public static function get_by_id($quest_id)
	{
		return static::query()->where('id', $quest_id)->get_one();
	}

	public static function get_by_url($quest_url)
	{
		return static::query()->where('url', $quest_url)->get_one();
	}

	public static function get_user_quests($user_id)
	{
		return static::query()->where('user_id', $user_id)->order_by('name', 'asc')->get();
	}

	public static function get_user_quest($user_id, $quest_url)
	{
		return static::query()->where('url', $quest_url)->get_one();
	}

	public static function create_quest($user_id, $name, $description, $purchase_within)
	{
		$quest = static::forge(array(
			'user_id'         => $user_id,
			'name'            => $name,
			'description'     => $description,
			'is_public'       => 1,
		));

		$quest->set_purchase_within($purchase_within);

		return $quest->save() ? $quest : null;
	}
}