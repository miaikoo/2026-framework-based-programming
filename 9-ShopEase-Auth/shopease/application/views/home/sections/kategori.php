<section id="kategori" class="pt-0">
	<div class="container">
		<div class="section-head">
			<div>
				<h2>Belanja per kategori</h2>
				<p>Mulai dari jenis kendaraan yang ingin kamu koleksi.</p>
			</div>
		</div>
		<div class="kategori-row">
			<?php foreach ($menu_kategori as $k): ?>
				<a class="kategori-item" href="<?= site_url('kategori/'.$k->slug) ?>">
					<span class="ikon" aria-hidden="true"><?= $k->ikon ?></span>
					<b><?= html_escape($k->nama) ?></b>
					<small><?= $k->jumlah ?> produk</small>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
