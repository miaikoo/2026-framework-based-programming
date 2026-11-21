<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
	protected $table = 'users';

	public function attempt($email, $password)
	{
		$user = $this->db->get_where($this->table, array('email' => strtolower(trim($email))))->row();
		return ($user && password_verify($password, $user->password)) ? $user : FALSE;
	}

	public function daftar($nama, $email, $no_wa, $password)
	{
		return $this->insert(array(
			'nama'       => $nama,
			'email'      => strtolower($email),
			'no_wa'      => $no_wa,
			'password'   => password_hash($password, PASSWORD_DEFAULT),
			'role'       => 'pembeli',
			'created_at' => date('Y-m-d H:i:s'),
		));
	}
}
