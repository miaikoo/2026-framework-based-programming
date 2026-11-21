<div class="container page-head pb-5">
	<div class="breadcrumb-se">
		<a href="<?= base_url() ?>">Beranda</a> / <a href="<?= site_url('kategori/'.$p->kategori) ?>"><?= html_escape($p->kategori_nama) ?></a> / <?= html_escape($p->nama) ?>
	</div>
	<div class="row g-5 mt-1">
		<div class="col-lg-6">
			<div class="detail-foto">
				<img src="<?= base_url($p->gambar) ?>" alt="Miniatur <?= html_escape($p->nama) ?>">
				<span class="tag-gantung" style="top:22px;right:20px;transform:scale(1.25);transform-origin:top right"><?php $this->load->view('partials/tag_harga', array('p' => $p)); ?></span>
			</div>
		</div>
		<div class="col-lg-6">
			<h1 style="font-size:clamp(2rem,3.4vw,2.8rem)"><?= html_escape($p->nama) ?></h1>
			<p class="fs-5 mb-1">
				<?php if ($p->diskon): ?>
					<s class="text-steel"><?= rupiah($p->harga) ?></s>
					<b style="color:var(--rail)"><?= rupiah($p->harga_akhir) ?></b>
					<span class="small text-steel">hemat <?= $p->diskon ?>% selama promo</span>
				<?php else: ?>
					<b><?= rupiah($p->harga_akhir) ?></b>
				<?php endif; ?>
			</p>
			<p class="measure mt-3"><?= html_escape($p->deskripsi) ?></p>
			<dl class="spesifikasi">
				<dt>Skala</dt><dd><?= $p->skala ?></dd>
				<dt>Bahan</dt><dd><?= html_escape($p->bahan) ?></dd>
				<dt>Kategori</dt><dd><?= html_escape($p->kategori_nama) ?></dd>
				<dt>Stok</dt><dd><?= $p->stok ?> unit<?= $p->stok <= 5 ? ', segera habis' : '' ?></dd>
				<dt>Terjual</dt><dd><?= $p->terjual ?> unit</dd>
			</dl>
			<?= form_open('keranjang/tambah', array('class' => 'd-flex flex-wrap align-items-center gap-2')) ?>
				<input type="hidden" name="slug" value="<?= $p->slug ?>">
				<label for="qty" class="visually-hidden">Jumlah</label>
				<input type="number" id="qty" name="qty" value="1" min="1" max="<?= min(10, $p->stok) ?>" class="form-control qty-input">
				<button type="submit" class="btn btn-se">Tambah ke keranjang</button>
			<?= form_close() ?>
			<p class="small text-steel mt-3 mb-0">Dikirim dari <?= html_escape($layanan->alamat) ?> lewat <?= implode(', ', $layanan->kurir) ?>.</p>
		</div>
	</div>

	<?php if ($serupa): ?>
		<h2 class="mt-5 mb-4">Dari kategori yang sama</h2>
		<div class="row g-4">
			<?php foreach ($serupa as $s): ?>
				<div class="col-6 col-lg-3"><?php $this->load->view('partials/kartu_produk', array('p' => $s)); ?></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
