<footer class="footer-se">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-4">
				<a class="brand mb-3" href="<?= base_url() ?>"><span class="brand-mark"><span></span></span>ShopEase</a>
				<p class="measure">Toko online miniatur dan barang koleksi. Semua pesanan dipilih, dicek, dan dikemas sendiri di <?= html_escape($layanan->alamat) ?>.</p>
			</div>
			<div class="col-6 col-lg-2">
				<h3>Kategori</h3>
				<ul>
					<?php foreach ($menu_kategori as $k): ?>
						<li><a href="<?= site_url('kategori/'.$k->slug) ?>"><?= html_escape($k->nama) ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="col-6 col-lg-2">
				<h3>Informasi</h3>
				<ul>
					<li><a href="<?= site_url('tentang') ?>">Tentang ShopEase</a></li>
					<li><a href="<?= site_url('cara-belanja') ?>">Cara belanja</a></li>
					<li><a href="<?= site_url('kontak') ?>">Hubungi kami</a></li>
					<li><a href="<?= site_url('keranjang') ?>">Keranjang</a></li>
				</ul>
			</div>
			<div class="col-lg-4">
				<h3>Layanan pelanggan</h3>
				<ul>
					<li>WhatsApp <?= $layanan->cs['wa'] ?></li>
					<li><?= $layanan->cs['email'] ?></li>
					<li><?= $layanan->cs['jam'] ?></li>
				</ul>
				<h3 class="mt-3">Pengiriman &amp; pembayaran</h3>
				<p class="mb-0"><?= implode(', ', $layanan->kurir) ?>. Transfer <?= implode(', ', $layanan->bank) ?>.</p>
			</div>
		</div>
		<div class="footer-bawah d-flex flex-wrap justify-content-between gap-2">
			<span>&copy; <?= date('Y') ?> ShopEase. Tugas 8 PBKK, dibuat dengan CodeIgniter <?= CI_VERSION ?>.</span>
			<span>Halaman dimuat dalam {elapsed_time} detik</span>
		</div>
	</div>
</footer>
