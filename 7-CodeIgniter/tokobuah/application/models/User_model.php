<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	private $table = 'users';

	/** User bisa login dengan username ATAU email */
	public function find_by_login($login)
	{
		return $this->db->where('username', $login)
			->or_where('email', $login)
			->get($this->table)->row();
	}

	/**
	 * Cocokkan password dengan hash di database.
	 * Mengembalikan object user jika benar, FALSE jika salah.
	 */
	public function attempt($login, $password)
	{
		$user = $this->find_by_login($login);
		if ($user && password_verify($password, $user->password))
		{
			$this->db->update($this->table, array('last_login' => date('Y-m-d H:i:s')), array('user_id' => $user->user_id));
			return $user;
		}
		return FALSE;
	}

	public function count_all()
	{
		return $this->db->count_all($this->table);
	}
}
