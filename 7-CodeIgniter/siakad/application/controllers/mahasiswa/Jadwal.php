<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends Mahasiswa_Controller
{
	public function index()
	{
		$this->load->model('kelas_model');
		$kelas = $this->kelas_model->by_mahasiswa($this->me->mahasiswa_id);

		// kelompokkan per hari untuk tampilan mingguan
		$per_hari = array_fill_keys(array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'), array());
		foreach ($kelas as $k)
		{
			$per_hari[$k->hari][] = $k;
		}

		$this->render('mahasiswa/jadwal', array(
			'title'     => 'Jadwal Kuliah',
			'per_hari'  => $per_hari,
			'total_sks' => array_sum(array_column($kelas, 'sks')),
			'hari_ini'  => Kelas_model::nama_hari(),
		));
	}
}
