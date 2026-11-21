<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Token "Ingat saya" dengan pola selector + validator.
 *  - selector  : acak, disimpan apa adanya, dipakai untuk mencari baris token
 *  - validator : acak, hanya hash SHA-256-nya yang disimpan
 * Jika database bocor, isi tabel ini tidak bisa dipakai untuk membuat cookie yang sah.
 */
class Remember_token_model extends CI_Model
{
	private $table = 'remember_tokens';

	/** @return string nilai cookie "selector:validator" */
	public function buat($id_user, $hari)
	{
		$selector  = bin2hex(random_bytes(12));   // 24 karakter
		$validator = bin2hex(random_bytes(32));   // 64 karakter
		$this->db->insert($this->table, array(
			'id_user'        => (int) $id_user,
			'selector'       => $selector,
			'validator_hash' => hash('sha256', $validator),
			'expires_at'     => date('Y-m-d H:i:s', time() + $hari * 86400),
			'created_at'     => date('Y-m-d H:i:s'),
		));
		return $selector.':'.$validator;
	}

	public function cari_selector($selector)
	{
		return $this->db->get_where($this->table, array('selector' => $selector))->row();
	}

	public function hapus($id)
	{
		$this->db->delete($this->table, array('id' => (int) $id));
	}

	public function hapus_selector($selector)
	{
		$this->db->delete($this->table, array('selector' => $selector));
	}

	/** Cabut semua perangkat yang diingat milik seorang pengguna */
	public function hapus_milik($id_user)
	{
		$this->db->delete($this->table, array('id_user' => (int) $id_user));
		return $this->db->affected_rows();
	}

	public function jumlah_aktif($id_user)
	{
		return $this->db->where('id_user', (int) $id_user)->where('expires_at >', date('Y-m-d H:i:s'))
			->count_all_results($this->table);
	}

	public function bersihkan_kedaluwarsa()
	{
		$this->db->delete($this->table, array('expires_at <' => date('Y-m-d H:i:s')));
	}
}
