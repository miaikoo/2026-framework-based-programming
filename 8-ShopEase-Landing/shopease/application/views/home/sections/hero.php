<section class="hero">
	<div class="container">
		<div class="row align-items-center g-5">
			<div class="col-lg-6">
				<h1>Miniatur kereta, kapal, dan kendaraan untuk rak koleksimu</h1>
				<p class="lead measure mt-3"><?= $total_produk ?> replika skala 1:10 sampai 1:700, dipilih dan dikemas sendiri di Yogyakarta. Pesan kapan saja tanpa harus datang ke toko.</p>
				<div class="d-flex flex-wrap gap-2 mt-4">
					<a class="btn btn-se" href="#terbaru">Lihat koleksi terbaru</a>
					<a class="btn btn-se btn-se-line" href="<?= site_url('cara-belanja') ?>">Cara belanja</a>
				</div>
				<ul class="fakta">
					<li>Pesanan diterima 24 jam</li>
					<li>Dikemas busa anti-benturan</li>
					<li>Konfirmasi bayar online</li>
					<li>Kirim ke seluruh Indonesia</li>
				</ul>
			</div>
			<div class="col-lg-6">
				<!-- Vitrin: pajangan produk seperti lemari kaca di toko miniatur -->
				<div class="vitrin">
					<div class="vitrin-grid">
						<a class="rak utama" href="<?= site_url('produk/'.$unggulan->slug) ?>">
							<img src="<?= base_url($unggulan->gambar) ?>" alt="Miniatur <?= html_escape($unggulan->nama) ?>">
							<span class="tag-gantung"><?php $this->load->view('partials/tag_harga', array('p' => $unggulan)); ?></span>
							<span class="nama-rak"><?= html_escape($unggulan->nama) ?>, <?= $unggulan->skala ?></span>
						</a>
						<?php foreach ($pajangan as $p): ?>
							<a class="rak" href="<?= site_url('produk/'.$p->slug) ?>">
								<img src="<?= base_url($p->gambar) ?>" alt="Miniatur <?= html_escape($p->nama) ?>">
								<span class="nama-rak"><?= html_escape($p->nama) ?></span>
							</a>
						<?php endforeach; ?>
					</div>
					<div class="vitrin-kaki"><span>Etalase minggu ini</span><a href="#terlaris" class="text-reset">Paling banyak dibeli</a></div>
				</div>
			</div>
		</div>
	</div>
</section>
