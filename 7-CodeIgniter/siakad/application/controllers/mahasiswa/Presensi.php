<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mahasiswa mengisi presensi dengan kode dari dosen.
 */
class Presensi extends Mahasiswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('kelas_model', 'pertemuan_model', 'presensi_model'));
		$this->pertemuan_model->tutup_kedaluwarsa();
	}

	public function index()
	{
		$this->render('mahasiswa/presensi', array(
			'title'      => 'Isi Presensi',
			'sesi_aktif' => $this->pertemuan_model->aktif_untuk_mahasiswa($this->me->mahasiswa_id),
		));
	}

	public function kirim()
	{
		$this->require_post();
		$this->form_validation->set_rules('kode', 'Kode presensi', 'trim|required|alpha_numeric|exact_length[6]');
		if ( ! $this->form_validation->run())
		{
			$this->flash('danger', strip_tags(form_error('kode')));
			redirect('mahasiswa/presensi');
		}

		$mid  = $this->me->mahasiswa_id;
		$sesi = $this->pertemuan_model->cari_kode_aktif($this->input->post('kode'));

		// pengecekan berurutan agar pesan kesalahannya jelas
		if ( ! $sesi)
		{
			$this->flash('danger', 'Kode tidak ditemukan atau sesi presensi sudah ditutup.');
		}
		elseif ( ! $this->kelas_model->is_peserta($sesi->kelas_id, $mid))
		{
			$this->flash('danger', 'Anda bukan peserta kelas untuk kode tersebut.');
		}
		elseif ($this->presensi_model->sudah_presensi($sesi->id, $mid))
		{
			$this->flash('info', 'Anda sudah tercatat pada pertemuan ini.');
		}
		else
		{
			$this->presensi_model->catat_hadir($sesi->id, $mid);
			$kelas = $this->kelas_model->detail($sesi->kelas_id);
			$this->flash('success', 'Presensi berhasil! Anda tercatat <b>hadir</b> di '.html_escape($kelas->matakuliah)
				.' pertemuan ke-'.$sesi->pertemuan_ke.' pukul '.date('H:i').'.');
		}
		redirect('mahasiswa/presensi');
	}
}
