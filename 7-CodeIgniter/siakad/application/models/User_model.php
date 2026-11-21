<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
	protected $table = 'users';

	public function attempt($username, $password)
	{
		$user = $this->db->get_where($this->table, array('username' => $username))->row();
		if ($user && password_verify($password, $user->password))
		{
			$this->update($user->id, array('last_login' => date('Y-m-d H:i:s')));
			return $user;
		}
		return FALSE;
	}

	/** Buat akun otomatis saat admin menambah dosen/mahasiswa. Password awal = username. */
	public function create_account($role, $username, $nama, $ref_id)
	{
		return $this->insert(array(
			'username'     => $username,
			'password'     => password_hash($username, PASSWORD_DEFAULT),
			'role'         => $role,
			'nama'         => $nama,
			'dosen_id'     => $role === 'dosen' ? $ref_id : NULL,
			'mahasiswa_id' => $role === 'mahasiswa' ? $ref_id : NULL,
		));
	}

	/** Sinkronkan username & nama akun bila data dosen/mahasiswa diubah. */
	public function sync_account($ref_field, $ref_id, $username, $nama)
	{
		$this->db->update($this->table, array('username' => $username, 'nama' => $nama), array($ref_field => $ref_id));
	}

	public function change_password($id, $new_password)
	{
		return $this->update($id, array('password' => password_hash($new_password, PASSWORD_DEFAULT)));
	}
}
