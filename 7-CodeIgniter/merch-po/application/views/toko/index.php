<?php if ( ! $batches): ?>
	<div class="callout callout-info">
		<h5>Belum ada PO yang dibuka</h5>
		<p class="mb-0">Pantau terus halaman ini atau Instagram himpunan untuk info PO berikutnya.</p>
	</div>
<?php endif; ?>

<?php foreach ($batches as $b): ?>
	<div class="hero-batch mb-4 d-md-flex justify-content-between align-items-center">
		<div class="mr-md-4">
			<span class="badge badge-warning mb-2">PO DIBUKA</span>
			<h2 class="font-weight-bold mb-1"><?= html_escape($b->nama) ?></h2>
			<p class="mb-2 text-white-50"><?= html_escape($b->deskripsi) ?></p>
			<small><i class="far fa-calendar-check mr-1"></i> Estimasi barang bisa diambil: <b><?= tgl($b->estimasi_ambil) ?></b></small>
		</div>
		<div class="text-md-right mt-3 mt-md-0 text-nowrap">
			<div class="small text-white-50">PO ditutup dalam</div>
			<div class="countdown" data-tutup="<?= $b->ditutup_at ?>"></div>
			<div class="small text-white-50"><?= tgl($b->ditutup_at, TRUE) ?> WIB</div>
		</div>
	</div>

	<div class="row">
		<?php foreach ($b->produk as $p): ?>
			<?php $sisa = Produk_model::sisa($p); ?>
			<div class="col-6 col-md-4 col-lg-3">
				<div class="card produk-card">
					<a href="<?= site_url('produk/'.$p->id) ?>"><img class="card-img-top" src="<?= base_url('upload/produk/'.$p->gambar) ?>" alt="<?= html_escape($p->nama) ?>"></a>
					<div class="card-body">
						<h6 class="mb-1"><a class="text-dark" href="<?= site_url('produk/'.$p->id) ?>"><?= html_escape($p->nama) ?></a></h6>
						<div class="harga"><?= rupiah($p->harga) ?></div>
						<div class="small text-muted mt-1">
							<?php if ($p->pakai_ukuran): ?><i class="fas fa-ruler-horizontal mr-1"></i>S – XXL<?php else: ?><i class="fas fa-box mr-1"></i>All size<?php endif; ?>
							<?php if ($sisa !== NULL): ?>
								&middot; <span class="<?= $sisa < 10 ? 'text-danger font-weight-bold' : '' ?>">sisa <?= $sisa ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endforeach; ?>

<?php if ($segera): ?>
	<h5 class="mt-2 mb-3"><i class="far fa-clock mr-1"></i> Segera dibuka</h5>
	<div class="row">
		<?php foreach ($segera as $b): ?>
			<div class="col-md-6">
				<div class="callout callout-warning">
					<h6 class="mb-1"><?= html_escape($b->nama) ?></h6>
					<p class="mb-1 small"><?= html_escape($b->deskripsi) ?></p>
					<small class="text-muted">Dibuka <?= tgl($b->dibuka_at, TRUE) ?> &ndash; <?= tgl($b->ditutup_at) ?></small>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="card card-outline card-secondary mt-2">
	<div class="card-body small">
		<b>Cara preorder:</b>
		1) pilih produk &amp; ukuran &rarr; 2) checkout &rarr; 3) transfer sesuai total &rarr; 4) unggah bukti &rarr;
		5) tunggu verifikasi &rarr; 6) ambil barang di sekretariat saat status <b>Siap diambil</b>.
	</div>
</div>
