<section id="promo" class="promo-band">
	<div class="container">
		<div class="row g-4 align-items-center">
			<div class="col-lg-5">
				<h2><?= html_escape($promo->judul) ?></h2>
				<p class="measure"><?= html_escape($promo->ket) ?> Berlaku sampai <?= tanggal_id($promo->selesai) ?>.</p>
				<div class="hitung" data-selesai="<?= $promo->selesai ?>" aria-label="Sisa waktu promo">
					<div><b data-unit="hari">--</b><small>hari</small></div>
					<div><b data-unit="jam">--</b><small>jam</small></div>
					<div><b data-unit="menit">--</b><small>menit</small></div>
				</div>
				<a class="btn btn-se mt-4" href="<?= site_url('kategori/'.$promo->kategori) ?>">Lihat semua kereta api</a>
			</div>
			<div class="col-lg-7">
				<div class="row g-2">
					<?php foreach ($promo->produk as $p): ?>
						<div class="col-4">
							<a class="promo-item" href="<?= site_url('produk/'.$p->slug) ?>">
								<img src="<?= base_url($p->gambar) ?>" alt="">
								<span>
									<b class="d-block lh-sm mb-1"><?= html_escape($p->nama) ?></b>
									<s><?= rupiah($p->harga) ?></s> <b><?= rupiah($p->harga_akhir) ?></b>
								</span>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
