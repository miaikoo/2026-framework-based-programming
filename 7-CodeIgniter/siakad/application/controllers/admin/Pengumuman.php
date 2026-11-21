<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengumuman_model');
	}

	public function index()
	{
		$this->render('admin/pengumuman/index', array(
			'title'      => 'Pengumuman',
			'pengumuman' => $this->pengumuman_model->all(),
		));
	}

	public function tambah()
	{
		$this->_form(NULL);
	}

	public function edit($id = NULL)
	{
		$row = $this->pengumuman_model->find($id) OR show_404();
		$this->_form($row);
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->pengumuman_model->find($id) OR show_404();
		$this->pengumuman_model->delete($row->id);
		$this->flash('success', 'Pengumuman dihapus.');
		redirect('admin/pengumuman');
	}

	private function _form($row)
	{
		$this->form_validation->set_rules($this->pengumuman_model->rules());
		if ($this->form_validation->run())
		{
			$data = array(
				'judul' => $this->input->post('judul', TRUE),
				'isi'   => $this->input->post('isi', TRUE),
				'untuk' => $this->input->post('untuk', TRUE),
			);
			if ($row)
			{
				$this->pengumuman_model->update($row->id, $data);
			}
			else
			{
				$data['user_id'] = $this->me->id;
				$data['created_at'] = date('Y-m-d H:i:s');
				$this->pengumuman_model->insert($data);
			}
			$this->flash('success', 'Pengumuman <b>'.html_escape($data['judul']).'</b> disimpan.');
			redirect('admin/pengumuman');
		}
		$this->render('admin/pengumuman/form', array('title' => $row ? 'Edit Pengumuman' : 'Buat Pengumuman', 'row' => $row));
	}
}
