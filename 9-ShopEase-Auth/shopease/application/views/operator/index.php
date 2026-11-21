<div class="container page-head pb-5">
	<?php $this->load->view('partials/panel_nav'); ?>
	<h1 class="mt-4" style="font-size:clamp(1.8rem,3vw,2.4rem)">Dashboard operator</h1>
	<p class="text-steel">Tugas harian staf toko: isi ulang stok yang menipis dan lengkapi data pelanggan untuk pengiriman.</p>

	<div class="angka-grid">
		<div class="angka<?= $menipis ? ' angka-waspada' : '' ?>"><b><?= count($menipis) ?></b><span>produk stok ≤ <?= $batas_stok ?></span></div>
		<div class="angka"><b><?= count($pelanggan) ?></b><span>pelanggan terdaftar</span></div>
		<div class="angka"><b><?= $belum_lengkap ?></b><span>pelanggan tanpa HP/alamat</span></div>
	</div>

	<div class="row g-4 mt-1">
		<div class="col-lg-5">
			<section class="kotak h-100">
				<h2 class="h5">Stok perlu diisi ulang</h2>
				<ul class="daftar-orang">
					<?php foreach ($menipis as $p): ?>
						<li>
							<img src="<?= base_url($p->gambar) ?>" alt="" class="thumb-kecil">
							<span class="flex-grow-1"><b><?= html_escape($p->nama) ?></b><small class="d-block text-steel"><?= html_escape($p->kategori_nama) ?>, skala <?= $p->skala ?></small></span>
							<span class="stok<?= $p->stok <= 3 ? ' stok-kritis' : '' ?>"><?= $p->stok ?> unit</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		</div>
		<div class="col-lg-7">
			<section class="kotak h-100">
				<h2 class="h5">Pelanggan</h2>
				<table class="table table-sm align-middle mb-0 tabel-pengguna">
					<thead><tr><th>Nama</th><th>HP</th><th>Alamat</th></tr></thead>
					<tbody>
						<?php foreach ($pelanggan as $p): ?>
							<tr>
								<td data-label="Nama"><b><?= html_escape($p->name) ?></b><small class="d-block text-steel"><?= html_escape($p->email) ?></small></td>
								<td data-label="HP" class="small"><?= $p->phone ? html_escape($p->phone) : '<span class="hasil hasil-gagal">belum ada</span>' ?></td>
								<td data-label="Alamat" class="small"><?= $p->address ? html_escape($p->address) : '<span class="hasil hasil-gagal">belum ada</span>' ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p class="small text-steel mt-3 mb-0">Operator hanya bisa melihat data pelanggan. Mengubah peran dan menghapus akun adalah wewenang admin.</p>
			</section>
		</div>
	</div>
</div>
