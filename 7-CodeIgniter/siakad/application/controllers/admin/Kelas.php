<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kelas & jadwal kuliah + pengaturan peserta */
class Kelas extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('kelas_model', 'matakuliah_model', 'dosen_model', 'mahasiswa_model'));
	}

	public function index()
	{
		$this->render('admin/kelas/index', array(
			'title' => 'Kelas & Jadwal',
			'kelas' => $this->kelas_model->detail_all(),
		));
	}

	public function tambah()
	{
		$this->_form(NULL);
	}

	public function edit($id = NULL)
	{
		$row = $this->kelas_model->find($id) OR show_404();
		$this->_form($row);
	}

	public function hapus($id = NULL)
	{
		$this->require_post();
		$row = $this->kelas_model->detail($id) OR show_404();
		$this->kelas_model->delete($row->id);
		$this->flash('success', 'Kelas <b>'.html_escape($row->matakuliah.' '.$row->nama_kelas).'</b> beserta riwayat presensinya dihapus.');
		redirect('admin/kelas');
	}

	/** Centang mahasiswa yang mengikuti kelas */
	public function peserta($id = NULL)
	{
		$kelas = $this->kelas_model->detail($id) OR show_404();

		if ($this->input->method() === 'post')
		{
			$ids = (array) $this->input->post('mahasiswa_id');
			$this->kelas_model->set_peserta($kelas->id, $ids);
			$this->flash('success', 'Peserta kelas <b>'.html_escape($kelas->matakuliah).'</b> disimpan ('.count($ids).' mahasiswa).');
			redirect('admin/kelas');
		}

		$this->render('admin/kelas/peserta', array(
			'title'     => 'Peserta Kelas',
			'kelas'     => $kelas,
			'mahasiswa' => $this->mahasiswa_model->all(),
			'terpilih'  => $this->kelas_model->peserta_ids($kelas->id),
		));
	}

	public function _setelah_jam_mulai($selesai)
	{
		if ($selesai <= $this->input->post('jam_mulai'))
		{
			$this->form_validation->set_message('_setelah_jam_mulai', '{field} harus setelah jam mulai.');
			return FALSE;
		}
		return TRUE;
	}

	private function _form($row)
	{
		$this->form_validation->set_rules($this->kelas_model->rules());
		if ($this->form_validation->run())
		{
			$data = array();
			foreach (array('matakuliah_id', 'dosen_id', 'nama_kelas', 'tahun_ajaran', 'hari', 'jam_mulai', 'jam_selesai', 'ruang') as $f)
			{
				$data[$f] = $this->input->post($f, TRUE);
			}
			$data['nama_kelas'] = strtoupper($data['nama_kelas']);

			// cegah duplikat MK + nama kelas + tahun ajaran (UNIQUE uq_kelas)
			$dup = $this->db->where(array('matakuliah_id' => $data['matakuliah_id'], 'nama_kelas' => $data['nama_kelas'], 'tahun_ajaran' => $data['tahun_ajaran']));
			if ($row) $dup->where('id !=', $row->id);
			if ($dup->count_all_results('kelas') > 0)
			{
				$this->flash('danger', 'Kelas '.$data['nama_kelas'].' untuk mata kuliah tersebut sudah ada pada tahun ajaran '.html_escape($data['tahun_ajaran']).'.');
			}
			else
			{
				$row ? $this->kelas_model->update($row->id, $data) : $this->kelas_model->insert($data);
				$this->flash('success', 'Kelas berhasil disimpan.');
				redirect('admin/kelas');
			}
		}

		$this->render('admin/kelas/form', array(
			'title'      => $row ? 'Edit Kelas' : 'Tambah Kelas',
			'row'        => $row,
			'opsi_mk'    => array_map(function ($m) { return $m->kode.' - '.$m->nama; }, array_column($this->matakuliah_model->all(), NULL, 'id')),
			'opsi_dosen' => $this->dosen_model->dropdown('nama'),
		));
	}
}
