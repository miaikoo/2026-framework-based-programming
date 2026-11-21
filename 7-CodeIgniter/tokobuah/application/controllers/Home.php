<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman depan toko (bisa diakses tanpa login).
 * URL: /            -> index()
 *      /produk/3    -> detail(3)   (lihat config/routes.php)
 */
class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
	}

	public function index()
	{
		$keyword  = trim((string) $this->input->get('q', TRUE));
		$category = $this->input->get('kategori', TRUE);

		$data = array(
			'title'    => 'Tokobuah - Buah Segar Setiap Hari',
			'keyword'  => $keyword,
			'category' => $category,
			'products' => $this->product_model->get_all($keyword, $category),
		);
		$this->load->view('home/index', $data);
	}

	public function detail($id = NULL)
	{
		$product = $this->product_model->get_by_id($id);
		if ( ! $product)
		{
			show_404();
		}

		$data = array(
			'title'   => $product->name.' - Tokobuah',
			'product' => $product,
		);
		$this->load->view('home/detail', $data);
	}
}
