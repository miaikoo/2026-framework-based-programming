<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman dosen: kelas yang diampu, buka/tutup sesi presensi, ubah status, rekap.
 *   /dosen/mengajar                 daftar kelas
 *   /dosen/mengajar/kelas/4         detail kelas + riwayat pertemuan
 *   /dosen/mengajar/buka/4          (POST) buka sesi baru
 *   /dosen/mengajar/sesi/41         kode presensi + daftar hadir
 *   /dosen/mengajar/status/41       (POST) simpan status izin/sakit/alpa
 *   /dosen/mengajar/tutup/41        (POST) tutup sesi
 *   /dosen/mengajar/rekap/4         rekap kehadiran & kelayakan UAS
 */
class Mengajar extends Dosen_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('kelas_model', 'pertemuan_model', 'presensi_model'));
		$this->pertemuan_model->tutup_kedaluwarsa();
	}

	public function index()
	{
		$kelas = $this->kelas_model->by_dosen($this->me->dosen_id);
		foreach ($kelas as $k)
		{
			$k->sesi_aktif = $this->pertemuan_model->aktif($k->id);
		}
		$this->render('dosen/index', array('title' => 'Kelas yang Saya Ampu', 'kelas' => $kelas));
	}

	public function kelas($id = NULL)
	{
		$kelas = $this->_kelas_saya($id);
		$this->render('dosen/kelas', array(
			'title'      => $kelas->matakuliah,
			'kelas'      => $kelas,
			'pertemuan'  => $this->pertemuan_model->by_kelas($kelas->id),
			'sesi_aktif' => $this->pertemuan_model->aktif($kelas->id),
			'berikutnya' => $this->pertemuan_model->next_number($kelas->id),
		));
	}

	public function buka($id = NULL)
	{
		$this->require_post();
		$kelas = $this->_kelas_saya($id);

		if ($this->pertemuan_model->aktif($kelas->id))
		{
			$this->flash('warning', 'Masih ada sesi yang dibuka di kelas ini. Tutup dulu sebelum membuka sesi baru.');
			redirect('dosen/mengajar/kelas/'.$kelas->id);
		}

		$this->form_validation->set_rules('topik', 'Topik', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('durasi', 'Durasi', 'required|in_list[10,15,30,60,90]');
		if ( ! $this->form_validation->run())
		{
			$this->flash('danger', validation_errors(' ', ' '));
			redirect('dosen/mengajar/kelas/'.$kelas->id);
		}

		$sesi = $this->pertemuan_model->buka($kelas->id, $this->input->post('topik', TRUE), (int) $this->input->post('durasi'));
		$this->flash('success', 'Sesi pertemuan ke-'.$sesi->pertemuan_ke.' dibuka. Tampilkan kode <b>'.$sesi->kode.'</b> kepada mahasiswa.');
		redirect('dosen/mengajar/sesi/'.$sesi->id);
	}

	public function sesi($id = NULL)
	{
		list($sesi, $kelas) = $this->_sesi_saya($id);
		$daftar = $this->presensi_model->daftar_pertemuan($sesi);

		$this->render('dosen/sesi', array(
			'title'  => 'Pertemuan ke-'.$sesi->pertemuan_ke,
			'sesi'   => $sesi,
			'kelas'  => $kelas,
			'daftar' => $daftar,
			'hadir'  => count(array_filter($daftar, function ($d) { return $d->status === 'H'; })),
		));
	}

	public function status($id = NULL)
	{
		$this->require_post();
		list($sesi, $kelas) = $this->_sesi_saya($id);

		$status     = (array) $this->input->post('status');
		$keterangan = (array) $this->input->post('keterangan');
		$peserta    = $this->kelas_model->peserta_ids($kelas->id);
		$diubah = 0;

		foreach ($status as $mahasiswa_id => $st)
		{
			// abaikan data yang tidak valid / bukan peserta kelas ini
			if ($st === '' || ! isset(Presensi_model::STATUS[$st]) || ! in_array((int) $mahasiswa_id, $peserta, TRUE))
			{
				continue;
			}
			$ket = trim(strip_tags((string) (isset($keterangan[$mahasiswa_id]) ? $keterangan[$mahasiswa_id] : '')));
			$this->presensi_model->simpan_status($sesi->id, (int) $mahasiswa_id, $st, mb_substr($ket, 0, 150));
			$diubah++;
		}

		$this->flash('success', 'Status presensi disimpan ('.$diubah.' mahasiswa).');
		redirect('dosen/mengajar/sesi/'.$sesi->id);
	}

	public function tutup($id = NULL)
	{
		$this->require_post();
		list($sesi, $kelas) = $this->_sesi_saya($id);

		if ($sesi->status === 'dibuka')
		{
			$this->pertemuan_model->tutup($sesi);
			$this->flash('success', 'Sesi ditutup. Mahasiswa yang belum presensi otomatis tercatat <b>Alpa</b>.');
		}
		redirect('dosen/mengajar/sesi/'.$sesi->id);
	}

	public function rekap($id = NULL)
	{
		$kelas = $this->_kelas_saya($id);
		$pertemuan = array_reverse($this->pertemuan_model->by_kelas($kelas->id));

		$this->render('dosen/rekap', array(
			'title'     => 'Rekap Kehadiran',
			'kelas'     => $kelas,
			'pertemuan' => $pertemuan,
			'rekap'     => $this->presensi_model->rekap_kelas($kelas->id),
			'matriks'   => $this->presensi_model->matriks_kelas($kelas->id),
		));
	}

	/* ---------- otorisasi: dosen hanya boleh mengakses kelasnya sendiri ---------- */

	private function _kelas_saya($id)
	{
		$kelas = $this->kelas_model->detail((int) $id);
		if ( ! $kelas || (int) $kelas->dosen_id !== (int) $this->me->dosen_id)
		{
			show_404();
		}
		return $kelas;
	}

	private function _sesi_saya($id)
	{
		$sesi = $this->pertemuan_model->find((int) $id) OR show_404();
		return array($sesi, $this->_kelas_saya($sesi->kelas_id));
	}
}
