<?php

class Controller_Friends extends Controller_App
{
	public function before()
	{
		parent::before();

		$this->require_auth();
	}


	public function get_index()
	{
		$this->user->mark_notice_seen('view_friends');

		$this->add_modal(View::forge('user/modal/start_quest'));
		$this->add_modal(View::forge('friends/modal/invite'));
		
		$this->template->body = View::forge('friends/index', array(
			'friends_quests' => $this->user->get_friends_quests(),
		));
	}

	public function get_view($friend_id)
	{
		$friendship = $this->user->get_friendship_by_id($friend_id);

		if (! isset($friendship))
		{
			$this->redirect('friends', 'error', 'You are not friends with this user');
		}

		$quests = $friendship->friend->get_public_quests();

		$this->add_modal(View::forge('user/modal/start_quest'));

		$this->template->body = View::forge('friends/view', array(
			'friendship'   => $friendship,
			'quests'       => $quests,
			'total_quests' => count($quests),   
		));
	}


	public function post_add()
	{
		$email = Input::post('email');

		$user = Model_User::get_by_email($email);

		if (! isset($user))
		{
			$this->redirect('friends', 'info', 'user does not exist');
		}

		if ($this->user->is_friend($user->id))
		{
			$this->redirect('friends', 'info', 'already friends');
		}

		$this->user->add_friend($user);
		$this->redirect('friends', 'success', 'friend added');
	}


	/**
	 * Hide friend
	 */
	public function get_hide($friend_id)
	{
		$friendship = $this->user->get_friendship_by_id($friend_id);

		if (! isset($friendship))
		{
			$this->redirect('friends', 'error', 'You are not friends with this user');
		}

		$friendship->hide();

		$this->redirect('friends', 'info', 'Friend removed');
	}


	/**
	 *
	 */
	public function get_refresh()
	{
		$total_added = $this->user->add_registered_facebook_friends();

		$this->redirect('friends', 'info', "$total_added friends have been added");
	}

}