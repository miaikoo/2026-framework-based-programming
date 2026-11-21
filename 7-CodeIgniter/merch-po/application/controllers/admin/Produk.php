<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Admin_Controller
{
	const UPLOAD_DIR = './upload/produk/';

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('produk_model', 'batch_model'));
	}

	public function index()
	{
		$this->render('admin/produk/index', array('title' => 'Produk', 'produk' => $this->produk_model->semua()));
	}

	public function tambah()
	{
		$this->_form(NULL);
	}

	public function edit($id = NULL)
	{
		$row = $this->produk_model->find((int) $id) OR show_404();
		$this->_form($row);
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->produk_model->find((int) $id) OR show_404();
		if ($this->db->where('produk_id', $row->id)->count_all_results('pesanan_item'))
		{
			$this->flash('danger', '<b>'.html_escape($row->nama).'</b> sudah pernah dipesan sehingga tidak bisa dihapus.');
		}
		else
		{
			$this->produk_model->delete($row->id);
			$this->_hapus_gambar($row->gambar);
			$this->flash('success', 'Produk <b>'.html_escape($row->nama).'</b> dihapus.');
		}
		redirect('admin/produk');
	}

	private function _form($row)
	{
		$this->form_validation->set_rules($this->produk_model->rules());
		$upload_error = '';

		if ($this->form_validation->run())
		{
			$data = array(
				'batch_id'     => (int) $this->input->post('batch_id'),
				'nama'         => $this->input->post('nama', TRUE),
				'harga'        => (int) $this->input->post('harga'),
				'pakai_ukuran' => $this->input->post('pakai_ukuran') ? 1 : 0,
				'kuota'        => $this->input->post('kuota') !== '' ? (int) $this->input->post('kuota') : NULL,
				'deskripsi'    => $this->input->post('deskripsi', TRUE),
			);

			$gambar = $this->_upload($data['nama']);
			if ($gambar !== FALSE)
			{
				if ($gambar)
				{
					if ($row) $this->_hapus_gambar($row->gambar);
					$data['gambar'] = $gambar;
				}
				$row ? $this->produk_model->update($row->id, $data) : $this->produk_model->insert($data);
				$this->flash('success', 'Produk <b>'.html_escape($data['nama']).'</b> disimpan.');
				redirect('admin/produk');
			}
			$upload_error = $this->upload->display_errors('', '');
		}

		$this->render('admin/produk/form', array(
			'title'        => $row ? 'Edit Produk' : 'Tambah Produk',
			'row'          => $row,
			'opsi_batch'   => $this->batch_model->dropdown('nama'),
			'upload_error' => $upload_error,
		));
	}

	/** @return string|null|false nama file, NULL bila tidak memilih file, FALSE bila gagal */
	private function _upload($nama)
	{
		if (empty($_FILES['gambar']['name'])) return NULL;
		$this->load->library('upload', array(
			'upload_path'   => self::UPLOAD_DIR,
			'allowed_types' => 'jpg|jpeg|png|webp',
			'max_size'      => 2048,
			'file_name'     => url_title($nama, '-', TRUE).'-'.time(),
		));
		return $this->upload->do_upload('gambar') ? $this->upload->data('file_name') : FALSE;
	}

	private function _hapus_gambar($file)
	{
		$path = self::UPLOAD_DIR.basename((string) $file);
		if ($file && $file !== 'default.png' && is_file($path)) unlink($path);
	}
}
