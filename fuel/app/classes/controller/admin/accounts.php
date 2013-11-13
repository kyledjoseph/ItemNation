<?php

class Controller_Admin_Accounts extends Controller_Admin
{
	public function before()
	{
		parent::before();

		$this->template->active_nav = 'accounts';
	}

	public function _get_account($user_id)
	{
		$user = Model_User::get_by_id($user_id);

		if (! $user)
		{
			$this->redirect('admin/accounts', 'error', "invalid user id '{$user_id}'");
		}

		return $user;
	}


	/**
	 * Account index
	 */
	public function get_index()
	{
		$accounts = Model_User::query()->order_by('created_at', 'desc')->get();

		$this->template->body = View::forge('admin/accounts/index', [
			'accounts' => $accounts,
		]);
	}


	/**
	 * View an account
	 */
	public function get_view($user_id)
	{
		$account = $this->_get_account($user_id);

		$this->template->body = View::forge('admin/accounts/view', [
			'account' => $account,
		]);
	}


	/**
	 * Edit an account
	 */
	public function get_edit($user_id)
	{
		$account = $this->_get_account($user_id);

		$this->template->body = View::forge('admin/accounts/edit', [
			'account' => $account,
		]);
	}

	public function post_edit($user_id)
	{
		$account = $this->_get_account($user_id);

		$fullname = Input::post('display_name');		
		$account->fullname = $fullname;
		$account->save();
		$this->redirect("admin/accounts/view/{$account->id}", 'success', 'account information updated');
	}


	/**
	 * Delete an account
	 */
	public function get_delete($user_id)
	{
		$account = $this->_get_account($user_id);

		$account->remove();
		$this->redirect('admin/accounts', 'success', "account deleted");
	}



	public function get_delete_quest($user_id, $quest_id)
	{
		$account = $this->_get_account($user_id);
		
		if (! $account->remove_quest($quest_id))
		{
			$this->redirect("admin/accounts/view/{$account->id}", 'error', 'could not remove quest');
		}

		$this->redirect("admin/accounts/view/{$account->id}", 'success', 'user quest removed');
	}
}