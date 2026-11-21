<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends MY_Model
{
	protected $table = 'produk';

	const UKURAN = array('S', 'M', 'L', 'XL', 'XXL');

	/** status pesanan yang dianggap "memakai" kuota */
	const STATUS_TERHITUNG = "'menunggu_pembayaran','menunggu_verifikasi','lunas','siap_diambil','selesai'";

	public function rules()
	{
		return array(
			array('field' => 'batch_id',  'label' => 'Batch PO',  'rules' => 'required|integer'),
			array('field' => 'nama',      'label' => 'Nama produk', 'rules' => 'trim|required|max_length[100]'),
			array('field' => 'harga',     'label' => 'Harga',     'rules' => 'required|is_natural_no_zero'),
			array('field' => 'kuota',     'label' => 'Kuota',     'rules' => 'trim|is_natural_no_zero'),
			array('field' => 'deskripsi', 'label' => 'Deskripsi', 'rules' => 'trim|max_length[1000]'),
		);
	}

	/** Query produk + nama batch + jumlah terpesan */
	private function base()
	{
		return $this->db->select('p.*, b.nama AS batch, b.dibuka_at, b.ditutup_at,
				(SELECT COALESCE(SUM(i.qty), 0) FROM pesanan_item i JOIN pesanan o ON o.id = i.pesanan_id
				 WHERE i.produk_id = p.id AND o.status IN ('.self::STATUS_TERHITUNG.')) AS terpesan', FALSE)
			->from('produk p')
			->join('batch_po b', 'b.id = p.batch_id');
	}

	public function semua()
	{
		return $this->base()->order_by('b.dibuka_at', 'DESC')->order_by('p.id')->get()->result();
	}

	public function by_batch($batch_id)
	{
		return $this->base()->where('p.batch_id', $batch_id)->order_by('p.id')->get()->result();
	}

	public function detail($id)
	{
		return $this->base()->where('p.id', $id)->get()->row();
	}

	/** Sisa kuota (NULL = tanpa batas) */
	public static function sisa($produk)
	{
		return $produk->kuota === NULL ? NULL : max(0, (int) $produk->kuota - (int) $produk->terpesan);
	}
}
