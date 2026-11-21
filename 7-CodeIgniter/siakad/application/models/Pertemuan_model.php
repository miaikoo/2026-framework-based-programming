<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sesi pertemuan kuliah + logika kode presensi.
 */
class Pertemuan_model extends MY_Model
{
	protected $table = 'pertemuan';

	/** Huruf yang mudah dibaca (tanpa O/0, I/1) */
	const ALFABET_KODE = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

	public function by_kelas($kelas_id)
	{
		return $this->db->select("p.*,
				SUM(pr.status = 'H') AS hadir,
				COUNT(pr.id) AS tercatat", FALSE)
			->from('pertemuan p')
			->join('presensi pr', 'pr.pertemuan_id = p.id', 'left')
			->where('p.kelas_id', $kelas_id)
			->group_by('p.id')
			->order_by('p.pertemuan_ke', 'DESC')
			->get()->result();
	}

	/** Sesi yang sedang dibuka pada suatu kelas (maks. satu) */
	public function aktif($kelas_id)
	{
		return $this->db->where(array('kelas_id' => $kelas_id, 'status' => 'dibuka'))
			->get($this->table)->row();
	}

	public function next_number($kelas_id)
	{
		$row = $this->db->select_max('pertemuan_ke')->get_where($this->table, array('kelas_id' => $kelas_id))->row();
		return (int) $row->pertemuan_ke + 1;
	}

	/**
	 * Buka sesi baru: buat kode acak 6 karakter yang berlaku $durasi menit.
	 */
	public function buka($kelas_id, $topik, $durasi)
	{
		$now = time();
		$id = $this->insert(array(
			'kelas_id'     => $kelas_id,
			'pertemuan_ke' => $this->next_number($kelas_id),
			'tanggal'      => date('Y-m-d', $now),
			'topik'        => $topik,
			'kode'         => $this->generate_kode(),
			'status'       => 'dibuka',
			'dibuka_at'    => date('Y-m-d H:i:s', $now),
			'berakhir_at'  => date('Y-m-d H:i:s', $now + $durasi * 60),
		));
		return $this->find($id);
	}

	/**
	 * Tutup sesi. Peserta yang belum presensi otomatis dicatat Alpa (A).
	 * INSERT IGNORE + UNIQUE(pertemuan_id, mahasiswa_id) menjaga presensi yang sudah ada.
	 */
	public function tutup($pertemuan)
	{
		$this->db->trans_start();
		$this->db->update($this->table, array(
			'status'      => 'ditutup',
			'berakhir_at' => min($pertemuan->berakhir_at, date('Y-m-d H:i:s')),
		), array('id' => $pertemuan->id));

		$this->db->query(
			"INSERT IGNORE INTO presensi (pertemuan_id, mahasiswa_id, status)
			 SELECT ?, pk.mahasiswa_id, 'A' FROM peserta_kelas pk WHERE pk.kelas_id = ?",
			array($pertemuan->id, $pertemuan->kelas_id)
		);
		$this->db->trans_complete();
	}

	/** Sesi yang melewati batas waktu ditutup otomatis (dipanggil saat halaman terkait dibuka). */
	public function tutup_kedaluwarsa()
	{
		$expired = $this->db->where('status', 'dibuka')
			->where('berakhir_at <', date('Y-m-d H:i:s'))
			->get($this->table)->result();
		foreach ($expired as $p)
		{
			$this->tutup($p);
		}
	}

	/** Cari sesi aktif berdasarkan kode yang diketik mahasiswa */
	public function cari_kode_aktif($kode)
	{
		return $this->db->where('kode', strtoupper(trim($kode)))
			->where('status', 'dibuka')
			->where('berakhir_at >=', date('Y-m-d H:i:s'))
			->get($this->table)->row();
	}

	/** Sesi aktif dari kelas-kelas yang diikuti seorang mahasiswa */
	public function aktif_untuk_mahasiswa($mahasiswa_id)
	{
		return $this->db->select('p.*, mk.nama AS matakuliah, d.nama AS dosen, pr.status AS status_saya')
			->from('pertemuan p')
			->join('kelas k', 'k.id = p.kelas_id')
			->join('matakuliah mk', 'mk.id = k.matakuliah_id')
			->join('dosen d', 'd.id = k.dosen_id')
			->join('peserta_kelas pk', 'pk.kelas_id = k.id AND pk.mahasiswa_id = '.(int) $mahasiswa_id)
			->join('presensi pr', 'pr.pertemuan_id = p.id AND pr.mahasiswa_id = '.(int) $mahasiswa_id, 'left')
			->where('p.status', 'dibuka')
			->where('p.berakhir_at >=', date('Y-m-d H:i:s'))
			->get()->result();
	}

	/** Semua sesi yang sedang dibuka (untuk dashboard admin) */
	public function semua_aktif()
	{
		return $this->db->select('p.*, mk.nama AS matakuliah, d.nama AS dosen, k.ruang')
			->from('pertemuan p')
			->join('kelas k', 'k.id = p.kelas_id')
			->join('matakuliah mk', 'mk.id = k.matakuliah_id')
			->join('dosen d', 'd.id = k.dosen_id')
			->where('p.status', 'dibuka')
			->get()->result();
	}

	private function generate_kode()
	{
		do
		{
			$kode = '';
			for ($i = 0; $i < 6; $i++)
			{
				$kode .= self::ALFABET_KODE[random_int(0, strlen(self::ALFABET_KODE) - 1)];
			}
			// pastikan tidak bentrok dengan sesi lain yang masih dibuka
			$bentrok = $this->db->where(array('kode' => $kode, 'status' => 'dibuka'))->count_all_results($this->table);
		}
		while ($bentrok);

		return $kode;
	}
}
