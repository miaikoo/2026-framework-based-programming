<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Catatan percobaan login.
 * Dipakai untuk (1) membatasi percobaan gagal dan (2) log aktivitas di dashboard admin.
 */
class Login_attempt_model extends CI_Model
{
	private $table = 'login_attempts';

	public function catat($email, $ip, $berhasil, $user_agent)
	{
		$this->db->insert($this->table, array(
			'email'      => strtolower(trim($email)),
			'ip_address' => $ip,
			'berhasil'   => $berhasil ? 1 : 0,
			'user_agent' => mb_substr((string) $user_agent, 0, 255),
			'created_at' => date('Y-m-d H:i:s'),
		));
	}

	/**
	 * Waktu terkunci berakhir (timestamp) bila email+IP sedang dikunci, atau NULL.
	 * Aturan: >= $batas kali gagal dalam $jendela menit terakhir (setelah login berhasil
	 * yang terakhir) mengunci login selama $kunci menit sejak kegagalan terakhir.
	 */
	public function terkunci_sampai($email, $ip, $batas, $jendela, $kunci)
	{
		$email = strtolower(trim($email));
		$sejak = date('Y-m-d H:i:s', time() - $jendela * 60);

		$sukses_terakhir = $this->db->select_max('created_at')
			->where(array('email' => $email, 'ip_address' => $ip, 'berhasil' => 1))
			->get($this->table)->row()->created_at;
		if ($sukses_terakhir && $sukses_terakhir > $sejak) $sejak = $sukses_terakhir;

		$gagal = $this->db->select('COUNT(*) AS n, MAX(created_at) AS terakhir', FALSE)
			->where(array('email' => $email, 'ip_address' => $ip, 'berhasil' => 0))
			->where('created_at >', $sejak)
			->get($this->table)->row();

		if ((int) $gagal->n < $batas) return NULL;
		$buka = strtotime($gagal->terakhir) + $kunci * 60;
		return $buka > time() ? $buka : NULL;
	}

	public function sisa_percobaan($email, $ip, $batas, $jendela)
	{
		$n = $this->db->where(array('email' => strtolower(trim($email)), 'ip_address' => $ip, 'berhasil' => 0))
			->where('created_at >', date('Y-m-d H:i:s', time() - $jendela * 60))
			->count_all_results($this->table);
		return max(0, $batas - $n);
	}

	public function terbaru($limit = 8)
	{
		return $this->db->select('a.*, u.name, u.role')
			->from($this->table.' a')
			->join('users u', 'u.email = a.email', 'left')
			->order_by('a.id', 'DESC')->limit($limit)->get()->result();
	}

	public function ringkasan_24jam()
	{
		$r = $this->db->select('COALESCE(SUM(berhasil = 1), 0) AS sukses, COALESCE(SUM(berhasil = 0), 0) AS gagal', FALSE)
			->where('created_at >=', date('Y-m-d H:i:s', time() - 86400))
			->get($this->table)->row();
		return (object) array('sukses' => (int) $r->sukses, 'gagal' => (int) $r->gagal);
	}
}
