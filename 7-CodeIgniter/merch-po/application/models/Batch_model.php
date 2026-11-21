<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Batch / gelombang preorder. Status dihitung dari tanggal, bukan disimpan,
 * sehingga PO otomatis tertutup begitu melewati ditutup_at.
 */
class Batch_model extends MY_Model
{
	protected $table = 'batch_po';
	protected $order = 'dibuka_at DESC';

	public function rules()
	{
		return array(
			array('field' => 'nama',           'label' => 'Nama batch',      'rules' => 'trim|required|max_length[100]'),
			array('field' => 'deskripsi',      'label' => 'Deskripsi',       'rules' => 'trim'),
			array('field' => 'dibuka_at',      'label' => 'Waktu buka',      'rules' => 'required'),
			array('field' => 'ditutup_at',     'label' => 'Waktu tutup',     'rules' => 'required|callback__setelah_buka'),
			array('field' => 'estimasi_ambil', 'label' => 'Estimasi ambil',  'rules' => 'required'),
			array('field' => 'rekening',       'label' => 'Rekening tujuan', 'rules' => 'trim|required|max_length[150]'),
		);
	}

	public static function status($batch)
	{
		$now = date('Y-m-d H:i:s');
		if ($now < $batch->dibuka_at) return 'segera';
		if ($now > $batch->ditutup_at) return 'ditutup';
		return 'dibuka';
	}

	public function dibuka()
	{
		$now = date('Y-m-d H:i:s');
		return $this->db->where('dibuka_at <=', $now)->where('ditutup_at >=', $now)
			->order_by('ditutup_at')->get($this->table)->result();
	}

	public function segera()
	{
		return $this->db->where('dibuka_at >', date('Y-m-d H:i:s'))->order_by('dibuka_at')->get($this->table)->result();
	}

	/** Batch + jumlah produk + jumlah pesanan sah */
	public function with_stats()
	{
		return $this->db->select("b.*,
				(SELECT COUNT(*) FROM produk p WHERE p.batch_id = b.id) AS jumlah_produk,
				(SELECT COUNT(*) FROM pesanan o WHERE o.batch_id = b.id AND o.status NOT IN ('dibatalkan','ditolak')) AS jumlah_pesanan", FALSE)
			->from('batch_po b')->order_by('b.dibuka_at', 'DESC')->get()->result();
	}
}
