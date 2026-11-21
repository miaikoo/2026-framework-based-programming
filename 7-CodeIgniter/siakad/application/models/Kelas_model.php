<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends MY_Model
{
	protected $table = 'kelas';

	const HARI = array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

	public function rules()
	{
		return array(
			array('field' => 'matakuliah_id', 'label' => 'Mata kuliah',  'rules' => 'required|integer'),
			array('field' => 'dosen_id',      'label' => 'Dosen',        'rules' => 'required|integer'),
			array('field' => 'nama_kelas',    'label' => 'Nama kelas',   'rules' => 'trim|required|alpha|max_length[2]'),
			array('field' => 'tahun_ajaran',  'label' => 'Tahun ajaran', 'rules' => 'trim|required|max_length[20]'),
			array('field' => 'hari',          'label' => 'Hari',         'rules' => 'required|in_list['.implode(',', self::HARI).']'),
			array('field' => 'jam_mulai',     'label' => 'Jam mulai',    'rules' => 'required|regex_match[/^\d{2}:\d{2}$/]'),
			array('field' => 'jam_selesai',   'label' => 'Jam selesai',  'rules' => 'required|regex_match[/^\d{2}:\d{2}$/]|callback__setelah_jam_mulai'),
			array('field' => 'ruang',         'label' => 'Ruang',        'rules' => 'trim|required|max_length[20]'),
		);
	}

	/** Query dasar kelas + nama MK + nama dosen + jumlah peserta */
	private function base()
	{
		return $this->db->select('k.*, mk.kode, mk.nama AS matakuliah, mk.sks, d.nama AS dosen,
				(SELECT COUNT(*) FROM peserta_kelas pk WHERE pk.kelas_id = k.id) AS jumlah_peserta', FALSE)
			->from('kelas k')
			->join('matakuliah mk', 'mk.id = k.matakuliah_id')
			->join('dosen d', 'd.id = k.dosen_id')
			->order_by("FIELD(k.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), k.jam_mulai", '', FALSE);
	}

	public function detail_all()
	{
		return $this->base()->get()->result();
	}

	public function detail($id)
	{
		return $this->base()->where('k.id', $id)->get()->row();
	}

	public function by_dosen($dosen_id)
	{
		return $this->base()->where('k.dosen_id', $dosen_id)->get()->result();
	}

	public function by_mahasiswa($mahasiswa_id)
	{
		return $this->base()
			->join('peserta_kelas pkm', 'pkm.kelas_id = k.id')
			->where('pkm.mahasiswa_id', $mahasiswa_id)
			->get()->result();
	}

	/** Kelas yang jadwalnya hari ini */
	public static function filter_hari_ini(array $kelas)
	{
		$hari_ini = self::nama_hari();
		return array_values(array_filter($kelas, function ($k) use ($hari_ini) { return $k->hari === $hari_ini; }));
	}

	public static function nama_hari($timestamp = NULL)
	{
		$map = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
		return $map[(int) date('w', $timestamp ?: time())];
	}

	/* ---------- peserta ---------- */

	public function peserta($kelas_id)
	{
		return $this->db->select('m.*')
			->from('peserta_kelas pk')
			->join('mahasiswa m', 'm.id = pk.mahasiswa_id')
			->where('pk.kelas_id', $kelas_id)
			->order_by('m.nim')
			->get()->result();
	}

	public function peserta_ids($kelas_id)
	{
		return array_map('intval', array_column(
			$this->db->select('mahasiswa_id')->get_where('peserta_kelas', array('kelas_id' => $kelas_id))->result_array(),
			'mahasiswa_id'));
	}

	/** Ganti seluruh daftar peserta dalam satu transaksi */
	public function set_peserta($kelas_id, array $mahasiswa_ids)
	{
		$this->db->trans_start();
		$this->db->delete('peserta_kelas', array('kelas_id' => $kelas_id));
		foreach (array_unique(array_map('intval', $mahasiswa_ids)) as $mid)
		{
			$this->db->insert('peserta_kelas', array('kelas_id' => $kelas_id, 'mahasiswa_id' => $mid));
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function is_peserta($kelas_id, $mahasiswa_id)
	{
		return $this->db->where(array('kelas_id' => $kelas_id, 'mahasiswa_id' => $mahasiswa_id))
			->count_all_results('peserta_kelas') > 0;
	}
}
