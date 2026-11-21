<?php // Kartu produk dipakai di landing, kategori, pencarian, dan produk serupa. Variabel: $p ?>
<article class="produk-card">
	<a class="foto" href="<?= site_url('produk/'.$p->slug) ?>" aria-label="<?= html_escape($p->nama) ?>">
		<img src="<?= base_url($p->gambar) ?>" alt="Miniatur <?= html_escape($p->nama) ?>" loading="lazy">
		<?php if ($p->baru): ?><span class="label-baru">Baru</span><?php endif; ?>
		<span class="tag-gantung"><?php $this->load->view('partials/tag_harga', array('p' => $p)); ?></span>
	</a>
	<div class="info">
		<a class="nama" href="<?= site_url('produk/'.$p->slug) ?>"><?= html_escape($p->nama) ?></a>
		<div class="meta">Skala <?= $p->skala ?>, <?= html_escape($p->bahan) ?></div>
		<?= form_open('keranjang/tambah') ?>
			<input type="hidden" name="slug" value="<?= $p->slug ?>">
			<input type="hidden" name="kembali" value="<?= html_escape(uri_string()) ?>">
			<button type="submit" class="btn btn-se btn-se-line btn-se-sm">Tambah ke keranjang</button>
		<?= form_close() ?>
	</div>
</article>
