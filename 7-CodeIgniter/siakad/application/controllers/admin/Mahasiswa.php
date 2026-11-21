<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mahasiswa_model');
		$this->crud_model = $this->mahasiswa_model;
	}

	public function index()
	{
		$this->render('admin/mahasiswa/index', array(
			'title'     => 'Data Mahasiswa',
			'mahasiswa' => $this->mahasiswa_model->all(),
		));
	}

	public function tambah()
	{
		$this->form_validation->set_rules($this->mahasiswa_model->rules());
		if ($this->form_validation->run())
		{
			$data = $this->_input();
			$this->db->trans_start();
			$id = $this->mahasiswa_model->insert($data);
			// akun login dibuat otomatis: username = password awal = NIM
			$this->user_model->create_account('mahasiswa', $data['nim'], $data['nama'], $id);
			$this->db->trans_complete();

			$this->flash('success', 'Mahasiswa <b>'.html_escape($data['nama']).'</b> ditambahkan. Akun login: '.$data['nim'].' / '.$data['nim']);
			redirect('admin/mahasiswa');
		}
		$this->render('admin/mahasiswa/form', array('title' => 'Tambah Mahasiswa', 'row' => NULL));
	}

	public function edit($id = NULL)
	{
		$row = $this->mahasiswa_model->find($id) OR show_404();
		$this->edit_id = $row->id;

		$this->form_validation->set_rules($this->mahasiswa_model->rules($row->id));
		if ($this->form_validation->run())
		{
			$data = $this->_input();
			$this->mahasiswa_model->update($row->id, $data);
			$this->user_model->sync_account('mahasiswa_id', $row->id, $data['nim'], $data['nama']);
			$this->flash('success', 'Data <b>'.html_escape($data['nama']).'</b> diperbarui.');
			redirect('admin/mahasiswa');
		}
		$this->render('admin/mahasiswa/form', array('title' => 'Edit Mahasiswa', 'row' => $row));
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->mahasiswa_model->find($id) OR show_404();
		// akun, peserta kelas, dan presensi ikut terhapus (ON DELETE CASCADE)
		$this->mahasiswa_model->delete($row->id);
		$this->flash('success', 'Mahasiswa <b>'.html_escape($row->nama).'</b> dihapus.');
		redirect('admin/mahasiswa');
	}

	private function _input()
	{
		return array(
			'nim'      => $this->input->post('nim', TRUE),
			'nama'     => $this->input->post('nama', TRUE),
			'gender'   => $this->input->post('gender', TRUE),
			'prodi'    => $this->input->post('prodi', TRUE),
			'angkatan' => (int) $this->input->post('angkatan'),
			'email'    => $this->input->post('email', TRUE),
		);
	}
}
