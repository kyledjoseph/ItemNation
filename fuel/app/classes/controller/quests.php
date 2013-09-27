<?php

class Controller_Quests extends Controller_App
{
	public function before()
	{
		parent::before();

		//$this->require_auth();
	}


	/**
	 * 
	 */
	private function get_quest_by_url($quest_url)
	{
		$quest = Model_Quest::get_by_url($quest_url);

		if (! isset($quest))
		{
			$this->redirect('/');
		}

		return $quest;
	}

	
	/**
	 * Show all quests of the current user
	 */
	// public function get_index()
	// {
	// 	$this->template->body = View::forge('quests/view', array(
	// 		'quests' => $this->user->get_quests(), //!
	// 	));
	// }


	/**
	 * View quest
	 */
	public function get_view($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);
		$sort  = Input::get('order');

		// private quest access
		if ($quest->is_private() and ! $quest->is_participant($this->user->id))
		{
			$this->redirect('/');
		}

		// clear notifications
		if ($this->user_logged_in() and $this->user->id == $quest->user_id)
		{
			$quest->mark_notifications_seen();
		}

		// quest product sort
		if (! empty($sort))
		{
			if (! $quest->is_sort_type($sort))
			{
				$this->redirect($quest->url());
			}
		
			$quest->set_active_sort($sort);
		}

		// quest products
		$quest_products = $quest->get_quest_products_sorted();

		// Casset::js('lib/jquery.expander.min.js');
		Casset::js('lib/jquery.tipTip.js');
		Casset::js('site/quest.js');
		Casset::js('site/quest/tour.js');
		Casset::js('fb/init.js');

		Casset::css('lib/tipTip.css');

		if ($this->user_logged_in())
		{
			$this->add_modal(View::forge('quests/modal/invite', array('quest' => $quest)));
			$this->add_modal(View::forge('quests/modal/add_product'));
			$this->add_modal(View::forge('quests/modal/edit_quest', array('quest' => $quest)));
			$this->add_modal(View::forge('quests/modal/delete_quest', array('quest' => $quest)));
		}

		$this->template->body = View::forge('quests/view', array(
			'quest'           => $quest,
			'quest_products'  => $quest_products,
			'total_products'  => count($quest_products),
			'quest_messages'  => $quest->get_messages(),
		));
	}



	/**
	 * add a message to the quest discussion
	 */
	public function post_message($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);
		$post  = $this->post_data('message');

		$this->require_auth($quest->url());

		$quest->new_message($this->user->id, $post->message);
		$this->redirect($quest->url());
	}


	/**
	 * add a product comment to a quest
	 */
	public function post_comment($quest_url, $quest_product_id)
	{
		$quest = $this->get_quest_by_url($quest_url);
		$post  = $this->post_data('comment');

		$this->require_auth($quest->url());

		$quest_product = $quest->get_quest_product($quest_product_id);

		if (! isset($quest_product))
		{
			$this->redirect($quest->url());
		}

		$quest_product->add_comment($quest_product->id, $this->user->id, $post->comment);
		
		$this->redirect($quest->url());
	}


	/**
	 * Like a quest product
	 */
	public function get_like($quest_url, $quest_product_id)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		$quest_product = $quest->get_quest_product($quest_product_id);
		isset($quest_product) or $this->redirect($quest->url(), 'info', 'Invalid product');

		// has the user already voted?
		if ($quest_product->has_user_voted($this->user->id))
		{
			$vote = $quest_product->user_get_vote($this->user->id);

			// change vote
			if ($vote->is_dislike())
			{
				$vote->change_to_like();
			}
		}
		else
		{
			$quest_product->like($this->user->id);
		}

		$this->redirect($quest->url());
	}


	/**
	 * Like a quest product
	 */
	public function get_dislike($quest_url, $quest_product_id)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		$quest_product = $quest->get_quest_product($quest_product_id);
		isset($quest_product) or $this->redirect($quest->url(), 'info', 'Invalid product');

		// has the user already voted?
		if ($quest_product->has_user_voted($this->user->id))
		{
			$vote = $quest_product->user_get_vote($this->user->id);

			// change vote
			if ($vote->is_like())
			{
				$vote->change_to_dislike();
			}
		}
		else
		{
			$quest_product->dislike($this->user->id);
		}

		$this->redirect($quest->url());
	}




	/**
	 * 
	 */
	public function post_create()
	{
		$post  = $this->post_data('name', 'description', 'purchase_within');

		$quest = $this->user->create_quest($post->name, $post->description, $post->purchase_within);
		
		$this->redirect($quest->url());
	}


	/**
	 * Edit quest
	 */
	public function get_edit($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);
		
		$this->require_auth($quest->url());

		if ($quest->user_id !== $this->user->id)
		{
			$this->redirect($quest->url());
		}

		$this->template->body = View::forge('quests/edit', array(
			'quest' => $quest,
		));
	}

	public function post_edit($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		if ($quest->user_id !== $this->user->id)
		{
			$this->redirect($quest->url());
		}

		$post = $this->post_data('name', 'description', 'purchase_within');

		$quest->name            = $post->name;
		$quest->description     = $post->description;
		$quest->set_purchase_within($post->purchase_within);

		$quest->save();

		$this->redirect($quest->url());
	}


	/**
	 * Delete a quest
	 */
	public function get_delete($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		if ($quest->user_id !== $this->user->id)
		{
			$this->redirect('/');
		}

		$quest->delete();
		$this->redirect('/');
	}


	/**
	 *
	 */
	// public function post_invite_friends($quest_url)
	// {
	// 	$quest = $this->get_quest_by_url($quest_url);
	// 	$this->require_auth($quest->url());

	// 	$post = $this->post_data('sg_friends', 'fb_friends');

	// 	$total_sg_friends = count($post->sg_friends);
	// 	$total_fb_friends = count($post->fb_friends);

	// 	if ($total_sg_friends > 0)
	// 	{
	// 		foreach ($post->sg_friends as $friend_id)
	// 		{
	// 			$friendship = $this->user->get_friendship_by_id($friend_id);
	// 			if (is_null($friendship))
	// 			{
	// 				throw new Exception("Error Processing Request", 1);
	// 				$quest->invite_friend_to_quest($friendship);
	// 			}
	// 		}
	// 	}
		
	// 	// handled client side
	// 	// if (! empty($post->fb_friends))
	// 	// {
	// 	// 	foreach ($post->fb_friends as $friend_id)
	// 	// 	{

	// 	// 	}
	// 	// }

	// 	if ($total_sg_friends > 0 or $total_fb_friends > 0)
	// 	{
	// 		$this->redirect($quest->url(), 'success', "Invitations sent");
	// 	}

	// 	$this->redirect($quest->url(), 'error', "No invitations sent");

	// }


	/**
	 * Invite a friend
	 */
	public function post_invite_email($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);
		$this->require_auth($quest->url());

		$post = $this->post_data('to', 'subject', 'description');
		$recipients = explode(',', $post->to);

		// ensure recipients
		if (! isset($post->to) or empty($post->to) or empty($recipients))
		{
			$this->redirect($quest->url(), 'error', "Enter one or more recipients to invite to this Quest");
		}
		
		foreach ($recipients as $recipient)
		{
			// is valid email


			// send email invitation
			$recipient = trim($recipient);
			$invite = Model_Invite_Email::send_invite($this->user, $quest, $recipient, $post->subject, $post->description);
		}

		if (count($recipients) > 1)
		{
			$this->redirect($quest->url(), 'info', "Invitations sent");
		}
		else
		{
			$this->redirect($quest->url(), 'info', "Invitation sent");
		}
		
	}

	
	/**
	 * Change public/private setting
	 */
	public function get_access($quest_url, $type)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		if ($quest->user_id !== $this->user->id)
		{
			$this->redirect($quest->url());
		}

		if (! in_array($type, array('public', 'private')))
		{
			$this->redirect($quest->url());
		}

		$quest->is_public = ($type == 'public' ? 1 : 0);
		$quest->save();

		$this->redirect($quest->url());
	}


	/**
	 * update purhase within time
	 */
	public function post_within($quest_url)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		if ($quest->user_id !== $this->user->id)
		{
			$this->redirect($quest->url());
		}

		$post = $this->post_data('purchase_within');

		$quest->set_purchase_within($post->purchase_within);
		$quest->save();

		$this->redirect($quest->url());
	}


	/**
	 * 
	 */
	public function get_remove($quest_url, $quest_product_id)
	{
		$quest = $this->get_quest_by_url($quest_url);
		$quest_product = $quest->get_quest_product($quest_product_id);

		$this->require_auth($quest->url());

		if (! $quest->belongs_to_user($this->user->id))
		{
			$this->redirect($quest->url());
		}

		if (! isset($quest_product))
		{
			$this->redirect($quest->url());
		}

		$quest_product->remove();
		$this->redirect($quest->url(), 'success', 'Product recomendation deleted');
	}


}