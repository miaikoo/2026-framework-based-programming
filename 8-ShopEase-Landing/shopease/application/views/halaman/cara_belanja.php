<div class="container page-head pb-5">
	<div class="breadcrumb-se"><a href="<?= base_url() ?>">Beranda</a> / Cara belanja</div>
	<h1 style="font-size:clamp(2rem,3.6vw,2.8rem)"><?= html_escape($judul) ?></h1>
	<p class="text-steel measure">Ikuti lima tahap ini. Kalau bingung di salah satu tahap, hubungi customer service lewat WhatsApp <?= $layanan->cs['wa'] ?>.</p>
	<ol class="rel">
		<?php foreach ($langkah as $l): ?>
			<li><b><?= html_escape($l->judul) ?></b><p><?= html_escape($l->isi) ?></p></li>
		<?php endforeach; ?>
	</ol>
	<div class="row g-4 mt-4">
		<div class="col-md-6">
			<div class="ringkasan h-100">
				<h2 class="h5">Kurir yang tersedia</h2>
				<?php foreach ($layanan->kurir as $k): ?><span class="chip"><?= html_escape($k) ?></span><?php endforeach; ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="ringkasan h-100">
				<h2 class="h5">Bank tujuan transfer</h2>
				<?php foreach ($layanan->bank as $b): ?><span class="chip"><?= html_escape($b) ?></span><?php endforeach; ?>
				<p class="small text-steel mt-2 mb-0">Nomor rekening ditampilkan setelah pesanan dibuat.</p>
			</div>
		</div>
	</div>
</div>
