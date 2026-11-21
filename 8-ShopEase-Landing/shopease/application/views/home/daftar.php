<?php // Dipakai untuk halaman kategori dan hasil pencarian ?>
<div class="container page-head">
	<div class="breadcrumb-se"><a href="<?= base_url() ?>">Beranda</a> / <?= $aktif ? 'Kategori' : 'Pencarian' ?></div>
	<h1 style="font-size:clamp(2rem,3.6vw,2.8rem)"><?= html_escape($judul) ?></h1>
	<p class="text-steel"><?= html_escape($subjudul) ?></p>
</div>

<div class="container pb-5">
	<div class="kategori-row mb-4">
		<?php foreach ($menu_kategori as $k): ?>
			<a class="kategori-item<?= $aktif === $k->slug ? ' aktif' : '' ?>" href="<?= site_url('kategori/'.$k->slug) ?>"<?= $aktif === $k->slug ? ' aria-current="page"' : '' ?>>
				<span class="ikon" aria-hidden="true"><?= $k->ikon ?></span>
				<b><?= html_escape($k->nama) ?></b>
				<small><?= $k->jumlah ?> produk</small>
			</a>
		<?php endforeach; ?>
	</div>

	<?php if ($aktif): ?>
		<form method="get" class="d-flex align-items-center gap-2 mb-4">
			<label for="urut" class="text-steel small">Urutkan</label>
			<select id="urut" name="urut" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
				<?php foreach (array('' => 'Terbaru', 'terlaris' => 'Terlaris', 'murah' => 'Harga terendah', 'mahal' => 'Harga tertinggi') as $v => $l): ?>
					<option value="<?= $v ?>" <?= (string) $urut === $v ? 'selected' : '' ?>><?= $l ?></option>
				<?php endforeach; ?>
			</select>
			<noscript><button class="btn btn-se btn-se-sm">Terapkan</button></noscript>
		</form>
	<?php endif; ?>

	<?php if ( ! $produk): ?>
		<div class="kosong">
			<div class="ikon" aria-hidden="true">🔍</div>
			<h2 class="h4 mt-2">Belum ada miniatur yang cocok</h2>
			<p class="text-steel">Coba kata lain seperti "kapal", "1:87", atau pilih salah satu kategori di atas.</p>
		</div>
	<?php else: ?>
		<div class="row g-4">
			<?php foreach ($produk as $p): ?>
				<div class="col-6 col-md-4 col-lg-3"><?php $this->load->view('partials/kartu_produk', array('p' => $p)); ?></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
