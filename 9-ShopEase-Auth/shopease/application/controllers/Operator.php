<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard operator (dijaga hook: 'operator' => array('operator', 'admin')).
 * Operator = staf toko: memantau stok yang menipis dan menghubungi pelanggan.
 * Operator hanya bisa melihat data pelanggan, tidak bisa mengubah peran atau menghapus akun.
 */
class Operator extends MY_Controller
{
	const BATAS_STOK = 6;

	public function index()
	{
		$pelanggan = $this->user_model->daftar_pengguna('customer');
		$menipis = array_values(array_filter($this->katalog_model->semua(), function ($p) { return $p->stok <= self::BATAS_STOK; }));
		usort($menipis, function ($a, $b) { return $a->stok - $b->stok; });

		$this->render('operator/index', array(
			'title'      => 'Dashboard operator — ShopEase',
			'pelanggan'  => $pelanggan,
			'belum_lengkap' => count(array_filter($pelanggan, function ($p) { return ! $p->phone || ! $p->address; })),
			'menipis'    => $menipis,
			'batas_stok' => self::BATAS_STOK,
		));
	}
}
