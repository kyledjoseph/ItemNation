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
			$this->redirect('/', 'error', 'Opps, an error occurs');
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

		if ($this->user_logged_in())
		{
			// clear notifications
			if ($this->user->id == $quest->user_id)
			{
				$quest->mark_notifications_seen();
			}

			// add users to private quests
			if ($quest->is_private() and ! $quest->is_participant($this->user->id))
			{
				$quest->add_participant($this->user->id);
			}
		}

		// quest product sort
		if (! empty($sort))
		{
			if (! $quest->is_sort_type($sort))
			{
                                $this->redirect($quest->url(), 'error', 'Opps, an error occurs');
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

		$this->template->body =  View::forge('quests/view', array(
                        'details'         => View::forge('quests/templates/details', array('quest' => $quest)),
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

		$message = $quest->new_message($this->user->id, $post->message);
		$this->redirect($quest->url(), 'success', 'your message has been posted', View::forge('quests/templates/message', array('message' => $message)) );
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
			$this->redirect($quest->url(), 'error', 'Opps, an error occurs');
		}

		$quest_product->add_comment($quest_product->id, $this->user->id, $post->comment);

		$this->redirect($quest->url(), 'success', 'Your comment has been added.');
	}


	/**
	 * Like a quest product
	 */
	public function get_like($quest_url, $quest_product_id)
	{
		$quest = $this->get_quest_by_url($quest_url);

		$this->require_auth($quest->url());

		$quest_product = $quest->get_quest_product($quest_product_id);
		isset($quest_product) or $this->redirect($quest->url(), 'error', 'Invalid product');

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

                $this->redirect($quest->url(), 'success', 'You have successfully like this product');
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

		$this->redirect($quest->url(), 'success', 'Product disliked');
	}




	/**
	 *
	 */
	public function post_create()
	{
		$post  = $this->post_data('name', 'description', 'purchase_within');

		$quest = $this->user->create_quest($post->name, $post->description, $post->purchase_within);
		$this->user->mark_notice_seen('start_quest');

		$this->redirect($quest->url(), 'success', 'Post created', View::forge('user/item', array('quest' => $quest)));
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
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
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
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
		}

		$post = $this->post_data('name', 'description', 'purchase_within');

		$quest->name            = $post->name;
		$quest->description     = $post->description;
		$quest->set_purchase_within($post->purchase_within);

		$quest->save();

		$this->redirect($quest->url(), 'success', 'Quest updated', View::forge('quests/templates/details', array('quest' => $quest, 'user' => $this->user)));
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
			$this->redirect('/', 'error', 'You cannot perform this operation');
		}

		$quest->delete();
		$this->redirect('/', 'success', 'Quest deleted');
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
			$this->redirect($quest->url(), 'success', "Invitations sent");
		}
		else
		{
			$this->redirect($quest->url(), 'success', "Invitation sent");
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
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
		}

		if (! in_array($type, array('public', 'private')))
		{
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
		}

		$quest->is_public = ($type == 'public' ? 1 : 0);
		$quest->save();

		$this->redirect($quest->url(), 'success', 'Access updated');
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
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
		}

		$post = $this->post_data('purchase_within');

		$quest->set_purchase_within($post->purchase_within);
		$quest->save();

		$this->redirect($quest->url(), 'success', 'Timeframe updated');
	}


	/**
	 *
	 */
	public function get_remove($quest_url, $quest_product_id)
	{
		$quest = $this->get_quest_by_url($quest_url);
		$quest_product = $quest->get_quest_product($quest_product_id);

		$this->require_auth($quest->url());

		if (! $quest->belongs_to_user($this->user))
		{
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
		}

		if (! isset($quest_product))
		{
			$this->redirect($quest->url(), 'error', 'You cannot perform this operation');
		}

		$quest_product->remove();
		$this->redirect($quest->url(), 'success', 'Product recomendation deleted');
	}


}
