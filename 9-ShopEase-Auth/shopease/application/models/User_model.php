<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	private $table = 'users';

	/** Kolom yang aman ditampilkan (tanpa password) */
	const KOLOM_PUBLIK = 'id_user, name, email, role, phone, address, last_login_at, created_at, updated_at';

	const PERAN = array('customer', 'operator', 'admin');

	public function cari($id)
	{
		return $this->db->get_where($this->table, array('id_user' => (int) $id))->row();
	}

	public function cari_by_email($email)
	{
		return $this->db->get_where($this->table, array('email' => strtolower(trim($email))))->row();
	}

	/** Akun baru dari form registrasi. Peran selalu customer. */
	public function daftar(array $data)
	{
		$now = date('Y-m-d H:i:s');
		$this->db->insert($this->table, array(
			'name'       => $data['name'],
			'email'      => strtolower($data['email']),
			'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
			'role'       => 'customer',
			'phone'      => $data['phone'] !== '' ? $data['phone'] : NULL,
			'address'    => $data['address'] !== '' ? $data['address'] : NULL,
			'created_at' => $now,
			'updated_at' => $now,
		));
		return $this->db->insert_id();
	}

	public function ubah_profil($id, array $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->update($this->table, $data, array('id_user' => (int) $id));
	}

	public function ganti_password($id, $password_baru)
	{
		return $this->ubah_profil($id, array('password' => password_hash($password_baru, PASSWORD_DEFAULT)));
	}

	public function catat_login($id)
	{
		$this->db->update($this->table, array('last_login_at' => date('Y-m-d H:i:s')), array('id_user' => (int) $id));
	}

	/** Daftar pengguna untuk admin/operator, bisa difilter peran & kata kunci */
	public function daftar_pengguna($peran = NULL, $q = NULL, $limit = NULL)
	{
		$this->db->select(self::KOLOM_PUBLIK)->from($this->table);
		if (in_array($peran, self::PERAN, TRUE)) $this->db->where('role', $peran);
		if ($q)
		{
			$this->db->group_start()->like('name', $q)->or_like('email', $q)->or_like('phone', $q)->group_end();
		}
		if ($limit) $this->db->limit($limit);
		return $this->db->order_by('created_at', 'DESC')->get()->result();
	}

	public function hitung_per_peran()
	{
		$hasil = array_fill_keys(self::PERAN, 0);
		foreach ($this->db->select('role, COUNT(*) AS n')->group_by('role')->get($this->table)->result() as $r)
		{
			$hasil[$r->role] = (int) $r->n;
		}
		return $hasil;
	}

	public function daftar_sejak($datetime)
	{
		return $this->db->where('created_at >=', $datetime)->count_all_results($this->table);
	}

	public function ubah_peran($id, $peran)
	{
		return $this->ubah_profil($id, array('role' => $peran));
	}

	public function hapus($id)
	{
		return $this->db->delete($this->table, array('id_user' => (int) $id));
	}

	public function email_dipakai_lain($email, $kecuali_id)
	{
		return $this->db->where('email', strtolower($email))->where('id_user !=', (int) $kecuali_id)
			->count_all_results($this->table) > 0;
	}
}
