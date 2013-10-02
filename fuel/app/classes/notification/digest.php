<?php

class Notification_Digest
{
	public $user;
	public $date;

	protected $quests;
	protected $total_notifications = 0;

	public function __construct($user, $date)
	{
		$this->user = $user;
		$this->date = $date;
		$this->quests = array();


		foreach ($user->get_quests() as $quest)
		{
			$notifications       = $quest->get_notifications_on_date($this->date);
			$total_notifications = count($notifications);

			if ($total_notifications > 0)
			{
				array_push($this->quests, $quest);
				$this->total_notifications += $total_notifications;
			}
		}
	}

	public function title()
	{
		$n_total = $this->total_notifications();
		$n_text  = Inflector::pluralize('notification', $n_total);
		$q_total = $this->total_quests();
		$q_text  = Inflector::pluralize('quest', $q_total);
		
		return "You have {$n_total} {$n_text} in {$q_total} {$q_text}";
	}

	public function has_notifications()
	{
		return ! empty($this->quests);
	}

	public function total_notifications()
	{
		return $this->total_notifications;
	}

	public function total_quests()
	{
		return count($this->quests);
	}

	public function get_quests()
	{
		return $this->quests;
	}

	public function send()
	{
		if (! $this->has_notifications())
		{
			return false;
		}

		// if (! $this->user->) receive daily digest return;
		$content = View::forge('emails/digest', array(
			'digest' => $this,
		));

		//echo var_export($this->quests);
		//echo '<hr>';

		Service_Email::send(array(
			'type'      => 'daily_digest',
			'to_addr'   => $this->user->email,
			'from_name' => 'ShopGab',
			'from_addr' => 'info@shopgab.com',
			'subject'   => 'ShopGab Daily Digest',
			'body'      => View::forge('emails/template', array(
				'content' => $content,
			)),
		));

			
	}

}