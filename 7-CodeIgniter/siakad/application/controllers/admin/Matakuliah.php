<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matakuliah extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('matakuliah_model');
		$this->crud_model = $this->matakuliah_model;
	}

	public function index()
	{
		$this->render('admin/matakuliah/index', array(
			'title'      => 'Mata Kuliah',
			'matakuliah' => $this->matakuliah_model->all(),
		));
	}

	public function tambah()
	{
		$this->_form(NULL);
	}

	public function edit($id = NULL)
	{
		$row = $this->matakuliah_model->find($id) OR show_404();
		$this->edit_id = $row->id;
		$this->_form($row);
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->matakuliah_model->find($id) OR show_404();
		if ($this->db->where('matakuliah_id', $row->id)->count_all_results('kelas') > 0)
		{
			$this->flash('danger', 'Mata kuliah <b>'.html_escape($row->nama).'</b> sudah dibuka sebagai kelas, tidak bisa dihapus.');
		}
		else
		{
			$this->matakuliah_model->delete($row->id);
			$this->flash('success', 'Mata kuliah <b>'.html_escape($row->nama).'</b> dihapus.');
		}
		redirect('admin/matakuliah');
	}

	/** tambah & edit memakai satu method karena alurnya sama */
	private function _form($row)
	{
		$this->form_validation->set_rules($this->matakuliah_model->rules($row ? $row->id : NULL));
		if ($this->form_validation->run())
		{
			$data = array(
				'kode'     => strtoupper($this->input->post('kode', TRUE)),
				'nama'     => $this->input->post('nama', TRUE),
				'sks'      => (int) $this->input->post('sks'),
				'semester' => (int) $this->input->post('semester'),
			);
			$row ? $this->matakuliah_model->update($row->id, $data) : $this->matakuliah_model->insert($data);
			$this->flash('success', 'Mata kuliah <b>'.html_escape($data['nama']).'</b> disimpan.');
			redirect('admin/matakuliah');
		}
		$this->render('admin/matakuliah/form', array('title' => $row ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah', 'row' => $row));
	}
}
