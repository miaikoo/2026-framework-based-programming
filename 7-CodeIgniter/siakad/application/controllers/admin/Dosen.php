<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dosen_model');
		$this->crud_model = $this->dosen_model;
	}

	public function index()
	{
		$this->render('admin/dosen/index', array(
			'title' => 'Data Dosen',
			'dosen' => $this->dosen_model->with_jumlah_kelas(),
		));
	}

	public function tambah()
	{
		$this->form_validation->set_rules($this->dosen_model->rules());
		if ($this->form_validation->run())
		{
			$data = $this->_input();
			$this->db->trans_start();
			$id = $this->dosen_model->insert($data);
			$this->user_model->create_account('dosen', $data['nidn'], $data['nama'], $id);
			$this->db->trans_complete();

			$this->flash('success', 'Dosen <b>'.html_escape($data['nama']).'</b> ditambahkan. Akun login: '.$data['nidn'].' / '.$data['nidn']);
			redirect('admin/dosen');
		}
		$this->render('admin/dosen/form', array('title' => 'Tambah Dosen', 'row' => NULL));
	}

	public function edit($id = NULL)
	{
		$row = $this->dosen_model->find($id) OR show_404();
		$this->edit_id = $row->id;

		$this->form_validation->set_rules($this->dosen_model->rules($row->id));
		if ($this->form_validation->run())
		{
			$data = $this->_input();
			$this->dosen_model->update($row->id, $data);
			$this->user_model->sync_account('dosen_id', $row->id, $data['nidn'], $data['nama']);
			$this->flash('success', 'Data <b>'.html_escape($data['nama']).'</b> diperbarui.');
			redirect('admin/dosen');
		}
		$this->render('admin/dosen/form', array('title' => 'Edit Dosen', 'row' => $row));
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->dosen_model->find($id) OR show_404();

		// dosen yang masih mengampu kelas tidak boleh dihapus (FK kelas.dosen_id tanpa CASCADE)
		if ($this->db->where('dosen_id', $row->id)->count_all_results('kelas') > 0)
		{
			$this->flash('danger', '<b>'.html_escape($row->nama).'</b> masih mengampu kelas. Pindahkan kelasnya ke dosen lain terlebih dahulu.');
			redirect('admin/dosen');
		}
		$this->dosen_model->delete($row->id);
		$this->flash('success', 'Dosen <b>'.html_escape($row->nama).'</b> dihapus.');
		redirect('admin/dosen');
	}

	private function _input()
	{
		return array(
			'nidn'  => $this->input->post('nidn', TRUE),
			'nama'  => $this->input->post('nama', TRUE),
			'email' => $this->input->post('email', TRUE),
		);
	}
}
