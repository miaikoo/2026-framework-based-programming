<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi_model extends MY_Model
{
	protected $table = 'presensi';

	/** Batas minimal kehadiran agar boleh mengikuti UAS (persen) */
	const BATAS_UAS = 75;

	const STATUS = array('H' => 'Hadir', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alpa');

	public function sudah_presensi($pertemuan_id, $mahasiswa_id)
	{
		return $this->db->where(array('pertemuan_id' => $pertemuan_id, 'mahasiswa_id' => $mahasiswa_id))
			->count_all_results($this->table) > 0;
	}

	public function catat_hadir($pertemuan_id, $mahasiswa_id)
	{
		return $this->insert(array(
			'pertemuan_id' => $pertemuan_id,
			'mahasiswa_id' => $mahasiswa_id,
			'status'       => 'H',
			'waktu'        => date('Y-m-d H:i:s'),
		));
	}

	/** Daftar peserta kelas beserta status presensinya pada satu pertemuan */
	public function daftar_pertemuan($pertemuan)
	{
		return $this->db->select('m.id AS mahasiswa_id, m.nim, m.nama, pr.status, pr.waktu, pr.keterangan')
			->from('peserta_kelas pk')
			->join('mahasiswa m', 'm.id = pk.mahasiswa_id')
			->join('presensi pr', 'pr.mahasiswa_id = m.id AND pr.pertemuan_id = '.(int) $pertemuan->id, 'left')
			->where('pk.kelas_id', $pertemuan->kelas_id)
			->order_by('m.nim')
			->get()->result();
	}

	/**
	 * Simpan perubahan status dari dosen (insert atau update).
	 * Waktu presensi asli dipertahankan; untuk status selain H waktu dikosongkan.
	 */
	public function simpan_status($pertemuan_id, $mahasiswa_id, $status, $keterangan)
	{
		$waktu = $status === 'H' ? date('Y-m-d H:i:s') : NULL;
		$this->db->query(
			"INSERT INTO presensi (pertemuan_id, mahasiswa_id, status, waktu, keterangan)
			 VALUES (?, ?, ?, ?, ?)
			 ON DUPLICATE KEY UPDATE
			   waktu = IF(VALUES(status) = 'H', COALESCE(waktu, VALUES(waktu)), NULL),
			   status = VALUES(status),
			   keterangan = VALUES(keterangan)",
			array($pertemuan_id, $mahasiswa_id, $status, $waktu, $keterangan !== '' ? $keterangan : NULL)
		);
	}

	/** Rekap per mahasiswa untuk satu kelas (dari view v_rekap_kehadiran) */
	public function rekap_kelas($kelas_id)
	{
		return $this->db->select('r.*, m.nim, m.nama')
			->from('v_rekap_kehadiran r')
			->join('mahasiswa m', 'm.id = r.mahasiswa_id')
			->where('r.kelas_id', $kelas_id)
			->order_by('m.nim')
			->get()->result();
	}

	/** Matriks status: [mahasiswa_id][pertemuan_id] => status */
	public function matriks_kelas($kelas_id)
	{
		$rows = $this->db->select('pr.mahasiswa_id, pr.pertemuan_id, pr.status')
			->from('presensi pr')
			->join('pertemuan p', 'p.id = pr.pertemuan_id')
			->where('p.kelas_id', $kelas_id)
			->get()->result();
		$m = array();
		foreach ($rows as $r)
		{
			$m[$r->mahasiswa_id][$r->pertemuan_id] = $r->status;
		}
		return $m;
	}

	/** Rekap semua kelas milik seorang mahasiswa */
	public function rekap_mahasiswa($mahasiswa_id)
	{
		return $this->db->select('r.*, mk.kode, mk.nama AS matakuliah, mk.sks, d.nama AS dosen, k.hari, k.jam_mulai')
			->from('v_rekap_kehadiran r')
			->join('kelas k', 'k.id = r.kelas_id')
			->join('matakuliah mk', 'mk.id = k.matakuliah_id')
			->join('dosen d', 'd.id = k.dosen_id')
			->where('r.mahasiswa_id', $mahasiswa_id)
			->order_by('mk.kode')
			->get()->result();
	}

	/** Riwayat presensi seorang mahasiswa di satu kelas */
	public function riwayat($kelas_id, $mahasiswa_id)
	{
		return $this->db->select('p.pertemuan_ke, p.tanggal, p.topik, p.status AS status_sesi, pr.status, pr.waktu, pr.keterangan')
			->from('pertemuan p')
			->join('presensi pr', 'pr.pertemuan_id = p.id AND pr.mahasiswa_id = '.(int) $mahasiswa_id, 'left')
			->where('p.kelas_id', $kelas_id)
			->order_by('p.pertemuan_ke')
			->get()->result();
	}

	/** Mahasiswa yang kehadirannya di bawah batas UAS (semua kelas) */
	public function di_bawah_batas()
	{
		return $this->db->select('r.*, m.nim, m.nama, mk.nama AS matakuliah')
			->from('v_rekap_kehadiran r')
			->join('mahasiswa m', 'm.id = r.mahasiswa_id')
			->join('kelas k', 'k.id = r.kelas_id')
			->join('matakuliah mk', 'mk.id = k.matakuliah_id')
			->where('r.total_pertemuan >', 0)
			->where('r.persen <', self::BATAS_UAS)
			->order_by('r.persen')
			->get()->result();
	}

	public static function boleh_uas($persen)
	{
		return $persen !== NULL && (float) $persen >= self::BATAS_UAS;
	}
}
