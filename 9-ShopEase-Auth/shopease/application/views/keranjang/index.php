<div class="container page-head pb-5">
	<div class="breadcrumb-se"><a href="<?= base_url() ?>">Beranda</a> / Keranjang</div>
	<h1 style="font-size:clamp(2rem,3.4vw,2.8rem)">Keranjang</h1>

	<?php if ( ! $isi): ?>
		<div class="kosong">
			<div class="ikon" aria-hidden="true">🧺</div>
			<h2 class="h4 mt-2">Keranjang masih kosong</h2>
			<p class="text-steel">Pilih miniatur dari etalase lalu tekan "Tambah ke keranjang".</p>
			<a class="btn btn-se" href="<?= base_url() ?>#terbaru">Lihat koleksi terbaru</a>
		</div>
	<?php else: ?>
		<div class="row g-4 mt-1">
			<div class="col-lg-8">
				<table class="table align-middle tabel-keranjang bg-white rounded-3 overflow-hidden">
					<thead><tr><th>Produk</th><th class="text-center">Jumlah</th><th class="text-end">Subtotal</th><th><span class="visually-hidden">Aksi</span></th></tr></thead>
					<tbody>
						<?php foreach ($isi as $i): ?>
							<tr>
								<td>
									<div class="d-flex align-items-center gap-3">
										<img src="<?= base_url($i->p->gambar) ?>" alt="">
										<div>
											<a class="fw-semibold text-decoration-none" href="<?= site_url('produk/'.$i->p->slug) ?>"><?= html_escape($i->p->nama) ?></a>
											<div class="small text-steel"><?= rupiah($i->p->harga_akhir) ?><?= $i->p->diskon ? ' (diskon '.$i->p->diskon.'%)' : '' ?></div>
										</div>
									</div>
								</td>
								<td class="text-center"><?= $i->qty ?></td>
								<td class="text-end"><?= rupiah($i->subtotal) ?></td>
								<td class="text-end">
									<?= form_open('keranjang/hapus') ?>
										<input type="hidden" name="slug" value="<?= $i->p->slug ?>">
										<button type="submit" class="btn btn-link btn-sm text-danger">Hapus</button>
									<?= form_close() ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-4">
				<div class="ringkasan">
					<h2 class="h5">Ringkasan</h2>
					<div class="d-flex justify-content-between mt-3"><span>Total belanja</span><b><?= rupiah($total) ?></b></div>
					<?php if ($hemat): ?><div class="d-flex justify-content-between small" style="color:var(--rail)"><span>Hemat dari promo</span><span><?= rupiah($hemat) ?></span></div><?php endif; ?>
					<div class="d-flex justify-content-between small text-steel"><span>Ongkos kirim</span><span>dihitung saat checkout</span></div>
					<hr>
					<button type="button" class="btn btn-se w-100" disabled>Checkout</button>
					<p class="small text-steel mt-2 mb-0">Checkout, ongkos kirim, dan konfirmasi pembayaran dikerjakan pada tugas berikutnya. Halaman ini fokus pada landing page dan keranjang.</p>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
