<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_model extends MY_Model
{
	protected $table = 'pesanan';
	protected $order = 'created_at DESC';

	/** status => array(label, warna badge, ikon) */
	const STATUS = array(
		'menunggu_pembayaran' => array('Menunggu pembayaran', 'warning',   'fa-wallet'),
		'menunggu_verifikasi' => array('Menunggu verifikasi', 'info',      'fa-hourglass-half'),
		'lunas'               => array('Lunas · diproduksi',  'primary',   'fa-check'),
		'ditolak'             => array('Pembayaran ditolak',  'danger',    'fa-times'),
		'siap_diambil'        => array('Siap diambil',        'success',   'fa-box-open'),
		'selesai'             => array('Selesai',             'secondary', 'fa-flag-checkered'),
		'dibatalkan'          => array('Dibatalkan',          'dark',      'fa-ban'),
	);

	/** aksi admin => array(status asal, status tujuan) */
	const TRANSISI = array(
		'verifikasi' => array('menunggu_verifikasi', 'lunas'),
		'tolak'      => array('menunggu_verifikasi', 'ditolak'),
		'siap'       => array('lunas', 'siap_diambil'),
		'selesai'    => array('siap_diambil', 'selesai'),
	);

	/** status yang dihitung sebagai pembayaran sah */
	const SAH = array('lunas', 'siap_diambil', 'selesai');

	/**
	 * Buat pesanan dari isi keranjang dalam satu transaksi.
	 * @return string kode pesanan
	 */
	public function buat($user_id, $batch_id, array $items, $total, $catatan)
	{
		$this->db->trans_start();
		$now = date('Y-m-d H:i:s');
		$id = $this->insert(array(
			'kode'       => uniqid('tmp'),   // diganti setelah id diketahui
			'user_id'    => $user_id,
			'batch_id'   => $batch_id,
			'total'      => $total,
			'status'     => 'menunggu_pembayaran',
			'catatan'    => $catatan ?: NULL,
			'created_at' => $now,
		));
		$kode = 'MH'.date('ymd').'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
		$this->update($id, array('kode' => $kode));

		foreach ($items as $it)
		{
			$this->db->insert('pesanan_item', array(
				'pesanan_id' => $id,
				'produk_id'  => $it->produk->id,
				'ukuran'     => $it->ukuran,
				'qty'        => $it->qty,
				'harga'      => $it->produk->harga,
				'subtotal'   => $it->subtotal,
			));
		}
		$this->db->trans_complete();
		return $this->db->trans_status() ? $kode : FALSE;
	}

	public function by_kode($kode)
	{
		return $this->db->select('o.*, u.nama AS pembeli, u.email, u.no_wa, b.nama AS batch, b.rekening, b.estimasi_ambil, b.ditutup_at')
			->from('pesanan o')
			->join('users u', 'u.id = o.user_id')
			->join('batch_po b', 'b.id = o.batch_id')
			->where('o.kode', $kode)->get()->row();
	}

	public function items($pesanan_id)
	{
		return $this->db->select('i.*, p.nama, p.gambar')
			->from('pesanan_item i')->join('produk p', 'p.id = i.produk_id')
			->where('i.pesanan_id', $pesanan_id)->order_by('i.id')->get()->result();
	}

	public function by_user($user_id)
	{
		return $this->db->select('o.*, b.nama AS batch, (SELECT SUM(qty) FROM pesanan_item WHERE pesanan_id = o.id) AS jumlah_barang', FALSE)
			->from('pesanan o')->join('batch_po b', 'b.id = o.batch_id')
			->where('o.user_id', $user_id)->order_by('o.created_at', 'DESC')->get()->result();
	}

	public function daftar_admin($status = NULL, $batch_id = NULL)
	{
		if ($status && isset(self::STATUS[$status])) $this->db->where('o.status', $status);
		if ($batch_id) $this->db->where('o.batch_id', (int) $batch_id);
		return $this->db->select('o.*, u.nama AS pembeli, u.no_wa, b.nama AS batch, (SELECT SUM(qty) FROM pesanan_item WHERE pesanan_id = o.id) AS jumlah_barang', FALSE)
			->from('pesanan o')->join('users u', 'u.id = o.user_id')->join('batch_po b', 'b.id = o.batch_id')
			->order_by("FIELD(o.status, 'menunggu_verifikasi') DESC, o.created_at DESC", '', FALSE)
			->get()->result();
	}

	public function hitung_per_status()
	{
		$hasil = array_fill_keys(array_keys(self::STATUS), 0);
		foreach ($this->db->select('status, COUNT(*) AS n')->group_by('status')->get($this->table)->result() as $r)
		{
			$hasil[$r->status] = (int) $r->n;
		}
		return $hasil;
	}

	public function pendapatan_sah($batch_id = NULL)
	{
		if ($batch_id) $this->db->where('batch_id', $batch_id);
		$r = $this->db->select_sum('total')->where_in('status', self::SAH)->get($this->table)->row();
		return (int) $r->total;
	}

	/** Jumlah terjual (pesanan sah) per produk dalam satu batch, untuk grafik */
	public function terjual_per_produk($batch_id)
	{
		// item dari pesanan yang belum sah tetap ikut ter-join (LEFT JOIN), jadi hanya
		// dijumlahkan bila pesanannya cocok (o.id tidak NULL)
		return $this->db->select('p.nama, COALESCE(SUM(IF(o.id IS NULL, 0, i.qty)), 0) AS qty', FALSE)
			->from('produk p')
			->join('pesanan_item i', 'i.produk_id = p.id', 'left')
			->join('pesanan o', "o.id = i.pesanan_id AND o.status IN ('lunas','siap_diambil','selesai')", 'left')
			->where('p.batch_id', $batch_id)
			->group_by('p.id')->order_by('p.id')->get()->result();
	}

	/**
	 * Rekap produksi: jumlah per produk per ukuran dari pesanan sah.
	 * @return array produk_id => (object) nama, pakai_ukuran, per_ukuran[S..XXL], total
	 */
	public function rekap_produksi($batch_id)
	{
		$rows = $this->db->select('p.id, p.nama, p.pakai_ukuran, i.ukuran, SUM(i.qty) AS qty')
			->from('pesanan_item i')
			->join('pesanan o', 'o.id = i.pesanan_id')
			->join('produk p', 'p.id = i.produk_id')
			->where('o.batch_id', $batch_id)
			->where_in('o.status', self::SAH)
			->group_by(array('p.id', 'i.ukuran'))
			->order_by('p.id')->get()->result();

		$rekap = array();
		foreach ($rows as $r)
		{
			if ( ! isset($rekap[$r->id]))
			{
				$rekap[$r->id] = (object) array('nama' => $r->nama, 'pakai_ukuran' => (bool) $r->pakai_ukuran,
					'per_ukuran' => array_fill_keys(Produk_model::UKURAN, 0), 'total' => 0);
			}
			if ($r->ukuran) $rekap[$r->id]->per_ukuran[$r->ukuran] = (int) $r->qty;
			$rekap[$r->id]->total += (int) $r->qty;
		}
		return $rekap;
	}

	/** Daftar pengambilan: siapa mengambil apa (pesanan sah) */
	public function daftar_ambil($batch_id)
	{
		return $this->db->select("o.kode, o.status, u.nama, u.no_wa,
				GROUP_CONCAT(CONCAT(p.nama, IF(i.ukuran IS NULL, '', CONCAT(' (', i.ukuran, ')')), ' x', i.qty) ORDER BY i.id SEPARATOR '; ') AS barang", FALSE)
			->from('pesanan o')->join('users u', 'u.id = o.user_id')
			->join('pesanan_item i', 'i.pesanan_id = o.id')->join('produk p', 'p.id = i.produk_id')
			->where('o.batch_id', $batch_id)->where_in('o.status', self::SAH)
			->group_by('o.id')->order_by('u.nama')->get()->result();
	}
}
