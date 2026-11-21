<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kehadiran extends Mahasiswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('kelas_model', 'pertemuan_model', 'presensi_model'));
		$this->pertemuan_model->tutup_kedaluwarsa();
	}

	public function index()
	{
		$this->render('mahasiswa/kehadiran', array(
			'title' => 'Rekap Kehadiran',
			'rekap' => $this->presensi_model->rekap_mahasiswa($this->me->mahasiswa_id),
		));
	}

	public function detail($kelas_id = NULL)
	{
		$mid = $this->me->mahasiswa_id;
		if ( ! $this->kelas_model->is_peserta((int) $kelas_id, $mid))
		{
			show_404();
		}
		$this->render('mahasiswa/kehadiran_detail', array(
			'title'   => 'Riwayat Presensi',
			'kelas'   => $this->kelas_model->detail((int) $kelas_id),
			'riwayat' => $this->presensi_model->riwayat((int) $kelas_id, $mid),
		));
	}
}
