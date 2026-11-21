<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Satu URL /dashboard, isi berbeda sesuai role yang login.
 */
class Dashboard extends Auth_Controller
{
	public function index()
	{
		$this->load->model(array('kelas_model', 'pertemuan_model', 'presensi_model', 'pengumuman_model'));
		$this->pertemuan_model->tutup_kedaluwarsa();

		$data = array(
			'title'      => 'Dashboard',
			'pengumuman' => $this->pengumuman_model->untuk_role($this->me->role, 4),
		);

		switch ($this->me->role)
		{
			case 'admin':
				$this->load->model(array('mahasiswa_model', 'dosen_model', 'matakuliah_model'));
				$data['jumlah'] = array(
					'mahasiswa'  => $this->mahasiswa_model->count(),
					'dosen'      => $this->dosen_model->count(),
					'matakuliah' => $this->matakuliah_model->count(),
					'kelas'      => $this->kelas_model->count(),
				);
				$data['sesi_aktif'] = $this->pertemuan_model->semua_aktif();
				$data['bawah_batas'] = $this->presensi_model->di_bawah_batas();
				break;

			case 'dosen':
				$kelas = $this->kelas_model->by_dosen($this->me->dosen_id);
				foreach ($kelas as $k)
				{
					$k->sesi_aktif = $this->pertemuan_model->aktif($k->id);
				}
				$data['kelas'] = $kelas;
				$data['hari_ini'] = Kelas_model::filter_hari_ini($kelas);
				break;

			case 'mahasiswa':
				$kelas = $this->kelas_model->by_mahasiswa($this->me->mahasiswa_id);
				$data['hari_ini'] = Kelas_model::filter_hari_ini($kelas);
				$data['sesi_aktif'] = $this->pertemuan_model->aktif_untuk_mahasiswa($this->me->mahasiswa_id);
				$data['rekap'] = $this->presensi_model->rekap_mahasiswa($this->me->mahasiswa_id);
				break;
		}

		$this->render('dashboard/'.$this->me->role, $data);
	}
}
