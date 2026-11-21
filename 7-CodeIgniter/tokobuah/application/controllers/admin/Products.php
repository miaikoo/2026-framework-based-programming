<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CRUD produk + upload gambar.
 *   GET  /admin/products            daftar produk
 *   GET  /admin/products/add        form tambah     (POST = simpan)
 *   GET  /admin/products/edit/3     form edit       (POST = simpan)
 *   POST /admin/products/delete     hapus (khusus role admin)
 */
class Products extends Admin_Controller
{
	const UPLOAD_DIR = './upload/product/';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
	}

	public function index()
	{
		$data = array(
			'title'    => 'Daftar Produk',
			'crumbs'   => array('Produk' => NULL),
			'products' => $this->product_model->get_all(NULL, NULL, 'product_id DESC'),
		);
		$this->load->view('admin/products/index', $data);
	}

	public function add()
	{
		$this->form_validation->set_rules($this->product_model->rules());

		if ($this->form_validation->run() === TRUE)
		{
			$data = $this->_form_data();

			$image = $this->_upload_image($data['name']);
			if ($image === FALSE)
			{
				return $this->_show_form('add', NULL);
			}
			$data['image'] = $image ?: 'default.png';

			$this->product_model->insert($data);
			$this->session->set_flashdata('success', 'Produk <b>'.html_escape($data['name']).'</b> berhasil ditambahkan.');
			redirect('admin/products');
		}

		$this->_show_form('add', NULL);
	}

	public function edit($id = NULL)
	{
		$product = $this->product_model->get_by_id($id);
		if ( ! $product)
		{
			show_404();
		}

		$this->form_validation->set_rules($this->product_model->rules());

		if ($this->form_validation->run() === TRUE)
		{
			$data = $this->_form_data();

			$image = $this->_upload_image($data['name']);
			if ($image === FALSE)
			{
				return $this->_show_form('edit', $product);
			}
			if ($image)
			{
				// gambar baru berhasil diupload, hapus gambar lama
				$this->_delete_image($product->image);
				$data['image'] = $image;
			}

			$this->product_model->update($product->product_id, $data);
			$this->session->set_flashdata('success', 'Produk <b>'.html_escape($data['name']).'</b> berhasil diperbarui.');
			redirect('admin/products');
		}

		$this->_show_form('edit', $product);
	}

	public function delete()
	{
		// hapus hanya lewat POST (form + token CSRF), bukan lewat link GET
		if ($this->input->method() !== 'post')
		{
			show_error('Metode tidak diizinkan', 405);
		}
		$this->only_role('admin');

		$product = $this->product_model->get_by_id($this->input->post('product_id'));
		if ($product)
		{
			$this->product_model->delete($product->product_id);
			$this->_delete_image($product->image);
			$this->session->set_flashdata('success', 'Produk <b>'.html_escape($product->name).'</b> sudah dihapus.');
		}
		redirect('admin/products');
	}

	/* ------------------------------------------------------------------ */

	private function _form_data()
	{
		return array(
			'name'        => $this->input->post('name', TRUE),
			'category'    => $this->input->post('category', TRUE),
			'price'       => (int) $this->input->post('price'),
			'unit'        => $this->input->post('unit', TRUE),
			'stock'       => (int) $this->input->post('stock'),
			'description' => $this->input->post('description', TRUE),
		);
	}

	private function _show_form($mode, $product)
	{
		$label = $mode === 'add' ? 'Tambah Produk' : 'Edit Produk';
		$this->load->view('admin/products/form', array(
			'title'        => $label,
			'crumbs'       => array('Produk' => 'admin/products', $label => NULL),
			'mode'         => $mode,
			'product'      => $product,
			'upload_error' => isset($this->upload) ? $this->upload->display_errors('', '') : '',
		));
	}

	/**
	 * Upload gambar produk.
	 * @return string|null|false  nama file baru, NULL jika tidak ada file dipilih, FALSE jika gagal
	 */
	private function _upload_image($product_name)
	{
		if (empty($_FILES['image']['name']))
		{
			return NULL;
		}

		$this->load->library('upload', array(
			'upload_path'   => self::UPLOAD_DIR,
			'allowed_types' => 'jpg|jpeg|png|webp',
			'max_size'      => 2048, // KB
			'file_name'     => url_title($product_name, '-', TRUE).'-'.time(),
			'overwrite'     => FALSE,
		));

		return $this->upload->do_upload('image') ? $this->upload->data('file_name') : FALSE;
	}

	private function _delete_image($filename)
	{
		$path = self::UPLOAD_DIR.basename($filename);
		if ($filename && $filename !== 'default.png' && is_file($path))
		{
			unlink($path);
		}
	}
}
