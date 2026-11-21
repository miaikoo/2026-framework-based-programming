<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard admin. Mewarisi Admin_Controller sehingga wajib login.
 * URL: /admin  atau  /admin/overview
 */
class Overview extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('product_model', 'user_model'));
	}

	public function index()
	{
		$products = $this->product_model->get_all();

		$data = array(
			'title'       => 'Dashboard',
			'crumbs'      => array('Dashboard' => NULL),
			'summary'     => $this->product_model->summary(),
			'total_user'  => $this->user_model->count_all(),
			'latest'      => $this->product_model->latest(5),
			// data grafik stok per produk
			'chart_label' => array_column($products, 'name'),
			'chart_stock' => array_map('intval', array_column($products, 'stock')),
		);
		$this->load->view('admin/overview', $data);
	}
}
