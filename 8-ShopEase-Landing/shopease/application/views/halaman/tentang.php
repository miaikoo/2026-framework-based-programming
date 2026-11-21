<div class="container page-head pb-5">
	<div class="breadcrumb-se"><a href="<?= base_url() ?>">Beranda</a> / Tentang</div>
	<div class="row g-5">
		<div class="col-lg-7">
			<h1 style="font-size:clamp(2rem,3.6vw,2.8rem)"><?= html_escape($judul) ?></h1>
			<p class="lead measure mt-3">ShopEase berawal dari toko miniatur rumahan di <?= html_escape($layanan->alamat) ?> yang dulu hanya menjual ke teman dan tetangga.</p>
			<div class="measure">
				<p>Dulu pembeli harus datang langsung, dan kabar diskon baru diketahui saat mereka mampir ke toko. Informasi stok dan transaksi harian juga dicatat manual sehingga butuh waktu lama untuk direkap.</p>
				<p>Lewat toko online ini, katalog bisa dilihat kapan saja, promo langsung tampil di halaman depan, dan pesanan bisa dibuat 24 jam tanpa bertemu penjual. Pemilik toko pun lebih mudah memantau stok dan transaksi.</p>
				<h2 class="h4 mt-4">Siapa yang memakai ShopEase</h2>
				<ul>
					<li><b>Pengunjung</b> melihat katalog dan bisa mendaftar menjadi pelanggan.</li>
					<li><b>Pelanggan</b> berbelanja, mengonfirmasi pembayaran, dan menulis testimoni.</li>
					<li><b>Administrator</b> mengelola produk, promo, pesanan, dan laporan.</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="ringkasan">
				<h2 class="h5">Dalam angka</h2>
				<dl class="spesifikasi mb-0">
					<dt>Produk</dt><dd><?= count($this->katalog_model->semua()) ?> miniatur</dd>
					<dt>Kategori</dt><dd><?= count($menu_kategori) ?></dd>
					<dt>Terjual</dt><dd><?= array_sum(array_column($this->katalog_model->semua(), 'terjual')) ?> unit</dd>
					<dt>Lokasi</dt><dd><?= html_escape($layanan->alamat) ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>
