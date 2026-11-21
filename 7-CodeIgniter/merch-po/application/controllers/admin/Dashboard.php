<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
	public function index()
	{
		$this->load->model(array('pesanan_model', 'batch_model'));
		$aktif = $this->batch_model->dibuka();
		$batch = $aktif ? $aktif[0] : NULL;

		$this->render('admin/dashboard', array(
			'title'      => 'Dashboard',
			'batch'      => $batch,
			'per_status' => $this->pesanan_model->hitung_per_status(),
			'pendapatan' => $batch ? $this->pesanan_model->pendapatan_sah($batch->id) : 0,
			'terjual'    => $batch ? $this->pesanan_model->terjual_per_produk($batch->id) : array(),
			'perlu_cek'  => $this->pesanan_model->daftar_admin('menunggu_verifikasi'),
		));
	}
}
