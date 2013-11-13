<?php

class Model_Analytics extends \Model
{

	protected $start_timestamp;
	protected $until_timestamp;

	protected $start_date;
	protected $until_date;


	/**
	 * 
	 */
	public function __construct($start_date = null, $until_date = null)
	{
		$this->start_date = is_null($start_date) ? date("Y-m-d") : $start_date;
		$this->until_date = is_null($until_date) ? $this->start_date : $until_date;

		$this->set_start_date($this->start_date);
		$this->set_until_date($this->until_date);
	}



	/**
	 * Report data - Users
	 */
	public function total_signups()
	{
		return Model_User::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->count();
	}

	public function get_signups()
	{
		return Model_User::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->get();
	}

	/**
	 * Report data - Quests
	 */
	public function total_quests_created()
	{
		return Model_Quest::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->count();
	}

	public function total_product_comments()
	{
		return Model_Quest_Product_Comment::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->count();
	}

	public function total_quest_messages()
	{
		return Model_Quest_Message::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->count();
	}

	public function total_comments_added()
	{
		return $this->total_product_comments() + $this->total_quest_messages();
	}

	public function get_quests()
	{
		return Model_Quest::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->get();
	}

	public function get_quest_products()
	{
		return Model_Quest_Product::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->get();
	}

	public function get_quest_product_comments()
	{
		return Model_Quest_Product_Comment::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->get();
	}

	public function get_quest_messages()
	{
		return Model_Quest_Message::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->get();
	}


	/**
	 * Report data - Emails
	 */
	public function total_emails_sent()
	{
		return Service_Email_Log::query()->where('created_at', 'between', array($this->start_timestamp, $this->until_timestamp))->count();
	}




	/**
	 * Analytics report information
	 */
	public function is_single_day()
	{
		return $this->start_date == $this->start_date;
	}

	public function report_title()
	{
		if ($this->is_single_day())
		{
			return $this->start_date_formatted();
		}

		return "{$this->start_date_formatted()} - {$this->until_date_formatted()}";
	}




	/**
	 * @var datestamp YYYY-MM-DD
	 */
	protected function set_start_date($datestamp)
	{
		$d = explode('-', $datestamp);
		$this->start_timestamp = mktime(0, 0, 0, $d[1], $d[2], $d[0]);
	}

	protected function set_until_date($datestamp)
	{
		$d = explode('-', $datestamp);
		$this->until_timestamp = mktime(23, 59, 59, $d[1], $d[2], $d[0]);
	}

	public function start_date()
	{
		return $this->start_date;
	}

	public function start_date_formatted($format = "r")
	{
		return date($format, $this->start_timestamp);
	}

	public function until_date_formatted($format = "r")
	{
		return date($format, $this->until_timestamp);
	}






	public function past_dates($total_days = 7)
	{
		$date  = new DateTime();
		$dates = array();

		for ($i = 0; $i < $total_days; $i++)
		{
			array_push($dates, $date->modify('-1 day')->format('Y-m-d'));
		}

		return $dates;
	}

	public function select_year()
	{
		return array('2013' => '2013');
	}

	public function select_month()
	{
		return array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	}

	public function select_day()
	{
		$days = array();
		for ($i=1; $i < 32; $i++)
		{
			$format = sprintf("%02s", $i);
			$days[$format] = $format;
		}
		return $days;
	}

	public function todays_year()
	{
		$date = new DateTime();
		return $date->format("Y");
	}

	public function todays_month()
	{
		$date = new DateTime();
		return $date->format("m");
	}

	public function todays_day()
	{
		$date = new DateTime();
		return $date->format("d");
	}

	public function get_csv($type)
	{
		if ($type == 'stats')
		{
			$csv = 'date,total_signups,total_quests_created,total_emails_sent,total_product_comments,total_quest_messages,total_comments_added' . "\n";
			$csv.= $this->start_date . ',';
			$csv.= $this->total_signups() . ',';
			$csv.= $this->total_quests_created() . ',';
			$csv.= $this->total_emails_sent() . ',';
			$csv.= $this->total_product_comments() . ',';
			$csv.= $this->total_quest_messages() . ',';
			$csv.= $this->total_comments_added();
			$csv.= "\n";
			return $csv;
		}
		
		if ($type == 'users')
		{
			$csv = 'id,username,email,display_name(),avatar_type,last_login,fb_friends_last_updated,created_at,updated_at' . "\n";
			foreach ($this->get_signups() as $user)
			{
				$csv.= "{$user->id},{$user->username},{$user->email},{$user->display_name()},{$user->avatar_type},{$user->last_login},{$user->fb_friends_last_updated},{$user->created_at},{$user->updated_at}\n";
			}
			return $csv;
		}

		if ($type == 'quests')
		{
			$csv = 'id,url,user_id,user_name,name,description,purchase_within,purchase_by,default_product_id,is_public,created_at,updated_at' . "\n";
			foreach ($this->get_quests() as $quest)
			{
				$csv.= "{$quest->id},{$quest->url},{$quest->user_id},{$quest->user->display_name()()},{$quest->name},{$quest->description},{$quest->purchase_within},{$quest->purchase_by},{$quest->default_product_id},{$quest->is_public},{$quest->created_at},{$quest->updated_at}";
			}
			return $csv;
		}

		if ($type == 'products')
		{
			$csv = 'id,quest_id,quest_name,quest_user_id,quest_user_name,product_id,description,total_likes,total_dislikes,added_by,added_by_name,created_at,updated_at' . "\n";
			foreach ($this->get_quest_products() as $quest_product)
			{
				$csv.= "{$qest_product->id},{$qest_product->quest_id},{$qest_product->quest->name},{$qest_product->quest->user_id},{$qest_product->quest->user->display_name()()},{$qest_product->product_id},{$qest_product->description},{$qest_product->total_likes},{$qest_product->total_dislikes},{$qest_product->added_by},{$qest_product->user->display_name()()},{$qest_product->created_at},{$qest_product->updated_at}";
			}
			return $csv;
		}
	
		if ($type == 'comments')
		{
			$csv = 'id,quest_product_id,quest_product_name,user_id,user_name,comment,created_at,updated_at' . "\n";
			foreach ($this->get_quest_product_comments() as $comment)
			{
				$csv.= "{$comment->id},{$comment->quest_product_id},{$comment->quest_product->quest->name},{$comment->user_id},{$comment->user->display_name()()},{$comment->comment},{$comment->created_at},{$comment->updated_at}";
			}
			return $csv;
		}

		if ($type == 'messages')
		{

			$csv = 'id,quest_id,quest_name,user_id,user_name,body,created_at,updated_at' . "\n";
			foreach ($this->get_quest_messages() as $message)
			{
				$csv.= "{$message->id},{$message->quest_id},{$message->quest->name},{$message->user_id},{$message->user->display_name()()},{$message->body},{$message->created_at},{$message->updated_at}";
			}
			return $csv;
		}
	}

}