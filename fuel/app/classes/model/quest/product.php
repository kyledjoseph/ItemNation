<?php

class Model_Quest_Product extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'quest_id',
		'product_id',
		'description',
		'total_likes',
		'total_dislikes',
		'vote_score',
		'added_by',
		'created_at',
		'updated_at',
	);


	protected static $_belongs_to = array(
		'quest' => array(
			'key_from' => 'quest_id',
			'model_to' => 'Model_Quest',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
		'user' => array(
			'key_from' => 'added_by',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
		'product' => array(
			'key_from' => 'product_id',
			'model_to' => 'Model_Product',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
	);

	// protected static $_has_one = array(
		
	// );

	protected static $_has_many = array(
		'votes' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Product_Vote',
			'key_to' => 'quest_product_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'comments' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Quest_Product_Comment',
			'key_to' => 'quest_product_id',
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
	);

	public function was_added_by_owner()
	{
		if (empty($this->added_by))
		{
			return true;
		}

		if ($this->added_by == $this->quest->user_id)
		{
			return true;
		}

		return false;
	}



	/**
	 * quest product votes
	 */
	public function total_likes()
	{
		$total = Model_Quest_Product_Vote::query()->where('quest_product_id', $this->id)->where('vote', '1')->count();
		return $total;
	}

	public function total_dislikes()
	{
		$total = Model_Quest_Product_Vote::query()->where('quest_product_id', $this->id)->where('vote', '0')->count();
		return $total;
	}

	public function cache_votes()
	{
		$this->total_likes    = $this->total_likes();
		$this->total_dislikes = $this->total_dislikes();
		$this->vote_score     = $this->total_likes - $this->total_dislikes;
		$this->save();
	}


	public function get_likes()
	{
		return Model_Quest_Product_Vote::query()->where('quest_product_id', $this->id)->where('vote', '1')->get();
	}

	public function get_dislikes()
	{
		return Model_Quest_Product_Vote::query()->where('quest_product_id', $this->id)->where('vote', '0')->get();
	}

	public function list_user_likes()
	{
		return $this->format_user_votes($this->get_likes());
	}

	public function list_user_dislikes()
	{
		return $this->format_user_votes($this->get_dislikes());
	}

	private function format_user_votes($votes)
	{
		$names = '';
		foreach ($votes as $vote)
		{
			$names.= $vote->user->display_name() . '<br>';
		}
		return $names;
	}

	public function has_user_voted($user_id)
	{
		$total = Model_Quest_Product_Vote::query()->where('quest_product_id', $this->id)->where('user_id', $user_id)->count();
		return $total > 0;
	}

	public function user_get_vote($user_id)
	{
		return Model_Quest_Product_Vote::query()->where('quest_product_id', $this->id)->where('user_id', $user_id)->get_one();
	}

	public function like($user_id)
	{
		$vote   = Model_Quest_Product_Vote::create_like($this->id, $user_id);
		$notice = Model_Notification::new_like($user_id, $this->quest, $vote->id);

		$this->quest->add_participant($user_id);
		
		return $vote and $this->cache_votes();
	}

	public function dislike($user_id)
	{
		$vote   = Model_Quest_Product_Vote::create_dislike($this->id, $user_id);
		$notice = Model_Notification::new_dislike($user_id, $this->quest, $vote->id);

		$this->quest->add_participant($user_id);

		return $vote and $this->cache_votes();
	}

	public function like_url()
	{
		return $this->quest->url() . '/like/' . $this->id;
	}

	public function dislike_url()
	{
		return $this->quest->url() . '/dislike/' . $this->id;
	}


	/**
	 *  quest product comments
	 */
	public function comment_url()
	{
		return $this->quest->url() . '/comment/' . $this->id;
	}

	public function total_comments()
	{
		return Model_Quest_Product_Comment::query()->where('quest_product_id', $this->id)->count();
	}

	public function total_comments_text()
	{
		$total = $this->total_comments();
		return $total . ' ' . Inflector::pluralize('Comment', $total);
	}

	public function has_comments()
	{
		return $this->total_comments() > 0;
	}

	public function get_comments()
	{
		return Model_Quest_Product_Comment::query()->where('quest_product_id', $this->id)->order_by('created_at', 'asc')->get();
	}

	public function add_comment($user_id, $text)
	{
		$comment = Model_Quest_Product_Comment::create_comment($this->id, $user_id, $text);
		$notice  = Model_Notification::new_comment($user_id, $this->quest, $comment->id);

		$this->quest->add_participant($user_id);

		return $comment;
	}


	public function remove_url()
	{
		return $this->quest->url() . '/remove/' . $this->id;
	}

	public function remove()
	{
		$this->delete();
	}

	public static function get_by_id($id)
	{
		return static::query()->where('id', $id)->get_one();
	}
}