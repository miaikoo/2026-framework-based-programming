<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller dasar ShopEase.
 * Menyiapkan data yang tampil di setiap halaman (menu kategori, jumlah isi keranjang)
 * dan method render() untuk membungkus view ke dalam layout utama.
 */
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('katalog_model');
		$this->load->vars(array(
			'menu_kategori'   => $this->katalog_model->kategori(),
			'jumlah_keranjang' => array_sum((array) $this->session->userdata('keranjang')),
			'layanan'         => $this->katalog_model->layanan(),
		));
	}

	/**
	 * @param string $view  view isi halaman
	 * @param array  $data  data untuk view; 'title' dipakai di <title>
	 */
	protected function render($view, array $data = array())
	{
		$data['content'] = $this->load->view($view, $data, TRUE);
		$this->load->view('layouts/main', $data);
	}

	/**
	 * Tampilkan halaman 404 ShopEase lalu hentikan proses.
	 * show_404() bawaan tidak melewati $route['404_override'], jadi dipakai method ini
	 * agar produk/kategori yang tidak ada tetap memakai tampilan yang sama.
	 */
	protected function tidak_ada()
	{
		$this->output->set_status_header(404);
		$this->render('halaman/tidak_ditemukan', array(
			'title' => 'Halaman tidak ditemukan — ShopEase',
			'saran' => $this->katalog_model->terlaris(3),
		));
		$this->output->_display();
		exit;
	}
}
