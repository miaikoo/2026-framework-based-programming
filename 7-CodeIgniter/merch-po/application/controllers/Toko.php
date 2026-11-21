<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Katalog untuk pengunjung (tanpa login) */
class Toko extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('batch_model', 'produk_model'));
	}

	public function index()
	{
		$batches = $this->batch_model->dibuka();
		foreach ($batches as $b)
		{
			$b->produk = $this->produk_model->by_batch($b->id);
		}

		$this->render('toko/index', array(
			'title'   => 'Preorder Merchandise Himpunan',
			'batches' => $batches,
			'segera'  => $this->batch_model->segera(),
		));
	}

	public function produk($id = NULL)
	{
		$produk = $this->produk_model->detail((int) $id) OR show_404();
		$batch  = $this->batch_model->find($produk->batch_id);

		$this->render('toko/produk', array(
			'title'  => $produk->nama,
			'produk' => $produk,
			'batch'  => $batch,
			'status' => Batch_model::status($batch),
			'sisa'   => Produk_model::sisa($produk),
		));
	}
}
