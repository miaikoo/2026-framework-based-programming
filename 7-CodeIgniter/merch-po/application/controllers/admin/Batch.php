<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('batch_model');
	}

	public function index()
	{
		$this->render('admin/batch/index', array('title' => 'Batch PO', 'batches' => $this->batch_model->with_stats()));
	}

	public function tambah()
	{
		$this->_form(NULL);
	}

	public function edit($id = NULL)
	{
		$row = $this->batch_model->find((int) $id) OR show_404();
		$this->_form($row);
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->batch_model->find((int) $id) OR show_404();
		$terpakai = $this->db->where('batch_id', $row->id)->count_all_results('produk')
			+ $this->db->where('batch_id', $row->id)->count_all_results('pesanan');
		if ($terpakai)
		{
			$this->flash('danger', 'Batch <b>'.html_escape($row->nama).'</b> sudah memiliki produk/pesanan sehingga tidak bisa dihapus.');
		}
		else
		{
			$this->batch_model->delete($row->id);
			$this->flash('success', 'Batch dihapus.');
		}
		redirect('admin/batch');
	}

	public function _setelah_buka($tutup)
	{
		if (strtotime($tutup) <= strtotime($this->input->post('dibuka_at')))
		{
			$this->form_validation->set_message('_setelah_buka', '{field} harus setelah waktu buka.');
			return FALSE;
		}
		return TRUE;
	}

	private function _form($row)
	{
		$this->form_validation->set_rules($this->batch_model->rules());
		if ($this->form_validation->run())
		{
			$data = array(
				'nama'           => $this->input->post('nama', TRUE),
				'deskripsi'      => $this->input->post('deskripsi', TRUE),
				// input datetime-local mengirim "2026-11-30T23:59"
				'dibuka_at'      => date('Y-m-d H:i:s', strtotime($this->input->post('dibuka_at'))),
				'ditutup_at'     => date('Y-m-d H:i:59', strtotime($this->input->post('ditutup_at'))),
				'estimasi_ambil' => date('Y-m-d', strtotime($this->input->post('estimasi_ambil'))),
				'rekening'       => $this->input->post('rekening', TRUE),
			);
			$row ? $this->batch_model->update($row->id, $data) : $this->batch_model->insert($data);
			$this->flash('success', 'Batch <b>'.html_escape($data['nama']).'</b> disimpan.');
			redirect('admin/batch');
		}
		$this->render('admin/batch/form', array('title' => $row ? 'Edit Batch PO' : 'Buat Batch PO', 'row' => $row));
	}
}
