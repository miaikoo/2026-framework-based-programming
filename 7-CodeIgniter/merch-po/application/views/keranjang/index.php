<h3 class="mb-3"><i class="fas fa-shopping-bag mr-1"></i> Keranjang</h3>

<?php if ( ! $cart['items']): ?>
	<div class="card"><div class="card-body text-center py-5">
		<div class="display-4 text-muted"><i class="fas fa-shopping-bag"></i></div>
		<p class="mt-2">Keranjang masih kosong.</p>
		<a href="<?= base_url() ?>" class="btn btn-warning">Lihat katalog</a>
	</div></div>
	<?php return; ?>
<?php endif; ?>

<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header"><b><?= html_escape($batch->nama) ?></b> <?= badge_batch(Batch_model::status($batch)) ?></div>
			<?= form_open('keranjang/ubah', array('id' => 'formUbah')) ?><?= form_close() ?>
			<div class="card-body p-0">
				<table class="table mb-0">
					<thead><tr><th>Produk</th><th>Ukuran</th><th style="width:110px">Jumlah</th><th class="text-right">Subtotal</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($cart['items'] as $it): ?>
							<tr>
								<td>
									<img class="thumb mr-2" src="<?= base_url('upload/produk/'.$it->produk->gambar) ?>" alt="">
									<?= html_escape($it->produk->nama) ?><br>
									<small class="text-muted ml-5 pl-3"><?= rupiah($it->produk->harga) ?></small>
								</td>
								<td class="align-middle"><?= $it->ukuran ?: '<span class="text-muted">-</span>' ?></td>
								<td class="align-middle">
									<input form="formUbah" type="number" name="qty[<?= $it->key ?>]" value="<?= $it->qty ?>" min="0" max="<?= Keranjang_belanja::MAKS_QTY ?>" class="form-control form-control-sm">
								</td>
								<td class="align-middle text-right"><?= rupiah($it->subtotal) ?></td>
								<td class="align-middle text-right">
									<?= form_open('keranjang/hapus') ?>
										<input type="hidden" name="key" value="<?= $it->key ?>">
										<button type="submit" class="btn btn-sm btn-link text-danger" title="Hapus"><i class="fas fa-trash"></i></button>
									<?= form_close() ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="card-footer text-right">
				<button type="submit" form="formUbah" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync-alt mr-1"></i> Perbarui jumlah</button>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card card-warning card-outline">
			<div class="card-header"><h3 class="card-title">Ringkasan</h3></div>
			<?= form_open('keranjang/checkout') ?>
				<div class="card-body">
					<div class="d-flex justify-content-between"><span><?= $this->keranjang->jumlah() ?> barang</span><b class="h4 mb-0"><?= rupiah($cart['total']) ?></b></div>
					<hr>
					<div class="form-group mb-0">
						<label for="catatan" class="small">Catatan untuk panitia (opsional)</label>
						<textarea id="catatan" name="catatan" rows="2" class="form-control form-control-sm" maxlength="255" placeholder="mis. diambil oleh teman"></textarea>
					</div>
				</div>
				<div class="card-footer">
					<?php if ($me && $me->role === 'pembeli'): ?>
						<button type="submit" class="btn btn-warning btn-block btn-lg"><i class="fas fa-check mr-1"></i> Buat pesanan</button>
					<?php elseif ($me): ?>
						<button type="button" class="btn btn-secondary btn-block" disabled>Admin tidak bisa memesan</button>
					<?php else: ?>
						<button type="submit" class="btn btn-warning btn-block btn-lg">Masuk untuk checkout</button>
					<?php endif; ?>
					<small class="text-muted d-block mt-2">Pembayaran via transfer ke <?= html_escape($batch->rekening) ?>.</small>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>
