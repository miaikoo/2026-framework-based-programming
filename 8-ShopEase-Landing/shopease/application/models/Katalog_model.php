<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sumber data katalog ShopEase.
 * Untuk tugas landing page ini data disimpan sebagai array agar tidak perlu database.
 * Controller dan view hanya memanggil method di bawah, jadi kalau nanti data dipindah
 * ke MySQL cukup isi method-method ini yang diganti dengan Query Builder.
 */
class Katalog_model extends CI_Model
{
	private $kategori = array(
		'kereta'      => array('nama' => 'Kereta api',             'ikon' => '🚂', 'ket' => 'Lokomotif, gerbong, dan trem skala HO'),
		'darat'       => array('nama' => 'Kendaraan darat',        'ikon' => '🚗', 'ket' => 'Mobil, motor, dan bus diecast'),
		'udara'       => array('nama' => 'Pesawat & helikopter',   'ikon' => '✈️', 'ket' => 'Replika maskapai dan helikopter'),
		'laut'        => array('nama' => 'Kapal',                  'ikon' => '⛵', 'ket' => 'Kapal layar kayu hingga kapal pesiar'),
		'tradisional' => array('nama' => 'Kendaraan tradisional',  'ikon' => '🛺', 'ket' => 'Becak, ontel, dan kendaraan desa'),
	);

	private $produk = array(
		array('slug' => 'lokomotif-uap',  'nama' => 'Lokomotif Uap C28',            'kategori' => 'kereta',      'skala' => '1:87',  'harga' => 425000, 'stok' => 6,  'terjual' => 41, 'masuk' => '2026-10-02', 'bahan' => 'Logam cor & plastik ABS', 'deskripsi' => 'Replika lokomotif uap dengan detail cerobong, roda penggerak, dan tender batu bara. Kompatibel dengan rel skala HO.'),
		array('slug' => 'kereta-argo',    'nama' => 'Rangkaian Kereta Eksekutif',   'kategori' => 'kereta',      'skala' => '1:87',  'harga' => 680000, 'stok' => 4,  'terjual' => 23, 'masuk' => '2026-11-14', 'bahan' => 'Plastik ABS',             'deskripsi' => 'Satu lokomotif dan tiga gerbong eksekutif dengan interior kursi. Bisa disambung dengan rangkaian lain.'),
		array('slug' => 'trem-klasik',    'nama' => 'Trem Batavia 1920',            'kategori' => 'kereta',      'skala' => '1:76',  'harga' => 310000, 'stok' => 9,  'terjual' => 12, 'masuk' => '2026-11-10', 'bahan' => 'Resin',                   'deskripsi' => 'Trem listrik bergaya awal abad ke-20 lengkap dengan pantograf dan papan rute.'),
		array('slug' => 'vespa-klasik',   'nama' => 'Vespa Sprint 1970',            'kategori' => 'darat',       'skala' => '1:18',  'harga' => 200000, 'stok' => 15, 'terjual' => 58, 'masuk' => '2026-09-20', 'bahan' => 'Diecast',                 'deskripsi' => 'Skuter klasik dengan setang dan roda yang bisa diputar. Cocok untuk pajangan meja kerja.'),
		array('slug' => 'mobil-sedan',    'nama' => 'Sedan Klasik 1985',            'kategori' => 'darat',       'skala' => '1:24',  'harga' => 300000, 'stok' => 7,  'terjual' => 19, 'masuk' => '2026-11-05', 'bahan' => 'Diecast',                 'deskripsi' => 'Sedan era 80-an dengan pintu dan kap mesin yang bisa dibuka.'),
		array('slug' => 'bus-antarkota',  'nama' => 'Bus Antarkota Double Decker',  'kategori' => 'darat',       'skala' => '1:76',  'harga' => 275000, 'stok' => 10, 'terjual' => 27, 'masuk' => '2026-11-17', 'bahan' => 'Diecast & plastik',       'deskripsi' => 'Bus tingkat dengan livery antarkota dan kaca jendela transparan.'),
		array('slug' => 'pesawat-jet',    'nama' => 'Pesawat Boeing 737-800',       'kategori' => 'udara',       'skala' => '1:200', 'harga' => 250000, 'stok' => 12, 'terjual' => 64, 'masuk' => '2026-08-30', 'bahan' => 'Diecast',                 'deskripsi' => 'Replika pesawat penumpang lengkap dengan dudukan akrilik dan roda pendarat.'),
		array('slug' => 'helikopter',     'nama' => 'Helikopter SAR',               'kategori' => 'udara',       'skala' => '1:72',  'harga' => 180000, 'stok' => 8,  'terjual' => 16, 'masuk' => '2026-11-12', 'bahan' => 'Plastik ABS',             'deskripsi' => 'Helikopter penyelamat dengan baling-baling yang bisa diputar dan winch di sisi pintu.'),
		array('slug' => 'kapal-pinisi',   'nama' => 'Kapal Pinisi Layar Kayu',      'kategori' => 'laut',        'skala' => '1:100', 'harga' => 350000, 'stok' => 5,  'terjual' => 37, 'masuk' => '2026-10-15', 'bahan' => 'Kayu jati & kain',        'deskripsi' => 'Pinisi buatan tangan perajin dengan tujuh layar kain dan lambung kayu jati.'),
		array('slug' => 'kapal-pesiar',   'nama' => 'Kapal Pesiar Nusantara',       'kategori' => 'laut',        'skala' => '1:700', 'harga' => 520000, 'stok' => 3,  'terjual' => 9,  'masuk' => '2026-11-18', 'bahan' => 'Resin',                   'deskripsi' => 'Kapal pesiar bertingkat dengan detail sekoci dan geladak kolam renang.'),
		array('slug' => 'mobil-balap',    'nama' => 'Mobil Balap Formula',          'kategori' => 'darat',       'skala' => '1:43',  'harga' => 150000, 'stok' => 20, 'terjual' => 45, 'masuk' => '2026-10-28', 'bahan' => 'Diecast',                 'deskripsi' => 'Mobil balap kursi tunggal dengan sayap depan-belakang dan ban slick karet.'),
		array('slug' => 'becak-jogja',    'nama' => 'Becak Jogja',                  'kategori' => 'tradisional', 'skala' => '1:12',  'harga' => 95000,  'stok' => 25, 'terjual' => 72, 'masuk' => '2026-09-10', 'bahan' => 'Kayu & logam',            'deskripsi' => 'Becak kayuh khas Yogyakarta dengan tenda lipat dan lukisan di sisi bodi.'),
		array('slug' => 'sepeda-ontel',   'nama' => 'Sepeda Ontel',                 'kategori' => 'tradisional', 'skala' => '1:10',  'harga' => 125000, 'stok' => 14, 'terjual' => 33, 'masuk' => '2026-10-05', 'bahan' => 'Logam',                   'deskripsi' => 'Sepeda ontel lengkap dengan boncengan, lampu depan, dan rantai yang bisa berputar.'),
		array('slug' => 'traktor-sawah',  'nama' => 'Traktor Sawah',                'kategori' => 'tradisional', 'skala' => '1:32',  'harga' => 140000, 'stok' => 11, 'terjual' => 8,  'masuk' => '2026-11-08', 'bahan' => 'Diecast',                 'deskripsi' => 'Traktor tangan untuk membajak sawah, lengkap dengan bajak yang bisa dilepas.'),
	);

	/** Diskon periode tertentu (di jurnal: info diskon langsung tampil secara real-time di website) */
	private $promo = array(
		'judul'    => 'Pekan Kereta Nusantara',
		'ket'      => 'Semua miniatur kereta api diskon 15% sampai akhir bulan.',
		'kategori' => 'kereta',
		'persen'   => 15,
		'mulai'    => '2026-11-18 00:00:00',
		'selesai'  => '2026-11-30 23:59:59',
	);

	/* ---------------- kategori ---------------- */

	public function kategori()
	{
		$hasil = array();
		foreach ($this->kategori as $slug => $k)
		{
			$k['slug'] = $slug;
			$k['jumlah'] = count($this->by_kategori($slug));
			$hasil[] = (object) $k;
		}
		return $hasil;
	}

	public function kategori_by_slug($slug)
	{
		return isset($this->kategori[$slug]) ? (object) ($this->kategori[$slug] + array('slug' => $slug)) : NULL;
	}

	/* ---------------- produk ---------------- */

	/** Semua produk, sudah dilengkapi harga setelah promo */
	public function semua()
	{
		return array_map(array($this, '_lengkapi'), $this->produk);
	}

	public function by_slug($slug)
	{
		foreach ($this->semua() as $p)
		{
			if ($p->slug === $slug) return $p;
		}
		return NULL;
	}

	public function by_kategori($slug)
	{
		return array_values(array_filter($this->semua(), function ($p) use ($slug) { return $p->kategori === $slug; }));
	}

	/** Produk terbaru = tanggal masuk paling akhir */
	public function terbaru($limit = 4)
	{
		$data = $this->semua();
		usort($data, function ($a, $b) { return strcmp($b->masuk, $a->masuk); });
		return array_slice($data, 0, $limit);
	}

	/** Produk terlaris = jumlah terjual terbanyak */
	public function terlaris($limit = 5)
	{
		$data = $this->semua();
		usort($data, function ($a, $b) { return $b->terjual - $a->terjual; });
		return array_slice($data, 0, $limit);
	}

	public function cari($kata)
	{
		$kata = mb_strtolower(trim($kata));
		if ($kata === '') return array();
		return array_values(array_filter($this->semua(), function ($p) use ($kata) {
			return mb_strpos(mb_strtolower($p->nama.' '.$p->deskripsi.' '.$p->skala), $kata) !== FALSE;
		}));
	}

	/** Produk lain dari kategori yang sama */
	public function serupa($produk, $limit = 3)
	{
		$lain = array_filter($this->by_kategori($produk->kategori), function ($p) use ($produk) { return $p->slug !== $produk->slug; });
		return array_slice(array_values($lain), 0, $limit);
	}

	/* ---------------- promo ---------------- */

	public function promo_aktif()
	{
		$now = date('Y-m-d H:i:s');
		if ($now < $this->promo['mulai'] || $now > $this->promo['selesai']) return NULL;
		$p = (object) $this->promo;
		$p->produk = $this->by_kategori($p->kategori);
		return $p;
	}

	/* ---------------- konten pendukung ---------------- */

	public function testimoni()
	{
		return array(
			(object) array('nama' => 'Raka Aditama', 'kota' => 'Semarang',  'produk' => 'Lokomotif Uap C28', 'isi' => 'Detail roda dan cerobongnya rapi sekali. Dikemas dengan busa tebal, sampai di rumah tanpa lecet.'),
			(object) array('nama' => 'Laras Wening', 'kota' => 'Bandung',   'produk' => 'Becak Jogja',      'isi' => 'Beli untuk oleh-oleh teman kantor. Admin cepat membalas waktu saya tanya ongkir ke Bandung.'),
			(object) array('nama' => 'Yosef Hartono', 'kota' => 'Surabaya', 'produk' => 'Kapal Pinisi Layar Kayu', 'isi' => 'Layarnya kain asli, bukan plastik. Sekarang jadi pajangan utama di ruang tamu.'),
		);
	}

	/** Alur belanja, mengikuti tahapan pada halaman selesai bayar di jurnal */
	public function langkah_belanja()
	{
		return array(
			(object) array('judul' => 'Masukkan ke keranjang', 'isi' => 'Pilih miniatur dan simpan di keranjang. Isi keranjang tetap tersimpan selama Anda menjelajah.'),
			(object) array('judul' => 'Pilih jasa pengiriman', 'isi' => 'Ongkos kirim dihitung dari kota tujuan dan kurir yang dipilih.'),
			(object) array('judul' => 'Pilih metode pembayaran', 'isi' => 'Transfer ke rekening bank toko sesuai total yang tertera.'),
			(object) array('judul' => 'Konfirmasi pembayaran', 'isi' => 'Masukkan ID pesanan dan jumlah transfer agar pesanan segera diproses.'),
			(object) array('judul' => 'Pesanan dikirim', 'isi' => 'Nomor resi dikirim ke email dan bisa dipantau di riwayat transaksi.'),
		);
	}

	public function layanan()
	{
		return (object) array(
			'kurir' => array('JNE', 'J&T Express', 'SiCepat', 'Pos Indonesia'),
			'bank'  => array('BNI', 'BCA', 'Mandiri'),
			'cs'    => array('wa' => '0812-0000-0000', 'email' => 'halo@shopease.test', 'jam' => 'Senin–Sabtu, 08.00–20.00 WIB'),
			'alamat' => 'Bantul, Daerah Istimewa Yogyakarta',
		);
	}

	/* ---------------- helper internal ---------------- */

	private function _lengkapi(array $p)
	{
		$p = (object) $p;
		$p->gambar = 'assets/img/produk/'.$p->slug.'.png';
		$p->kategori_nama = $this->kategori[$p->kategori]['nama'];
		$p->diskon = 0;
		$now = date('Y-m-d H:i:s');
		if ($p->kategori === $this->promo['kategori'] && $now >= $this->promo['mulai'] && $now <= $this->promo['selesai'])
		{
			$p->diskon = $this->promo['persen'];
		}
		$p->harga_akhir = (int) round($p->harga * (100 - $p->diskon) / 100, -2);
		$p->baru = (strtotime($p->masuk) >= strtotime('-14 days'));
		return $p;
	}
}
