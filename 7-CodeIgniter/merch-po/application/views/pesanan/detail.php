<?php $boleh_upload = in_array($o->status, array('menunggu_pembayaran', 'ditolak'), TRUE); ?>
<div class="d-flex justify-content-between align-items-center mb-2">
	<h3 class="mb-0">Pesanan <?= $o->kode ?></h3>
	<?= badge_status($o->status) ?>
</div>
<p class="text-muted"><?= html_escape($o->batch) ?> &middot; dipesan <?= tgl($o->created_at, TRUE) ?></p>

<div class="card"><div class="card-body py-2"><?php $this->load->view('pesanan/_timeline'); ?></div></div>

<div class="row">
	<div class="col-lg-7">
		<div class="card">
			<div class="card-header"><h3 class="card-title">Rincian barang</h3></div>
			<div class="card-body p-0">
				<table class="table mb-0">
					<?php foreach ($items as $it): ?>
						<tr>
							<td><img class="thumb mr-2" src="<?= base_url('upload/produk/'.$it->gambar) ?>" alt=""><?= html_escape($it->nama) ?><?= $it->ukuran ? ' <span class="badge badge-light">'.$it->ukuran.'</span>' : '' ?></td>
							<td class="align-middle text-nowrap"><?= $it->qty ?> &times; <?= rupiah($it->harga) ?></td>
							<td class="align-middle text-right"><?= rupiah($it->subtotal) ?></td>
						</tr>
					<?php endforeach; ?>
					<tr class="bg-light"><th colspan="2">Total</th><th class="text-right h5 mb-0"><?= rupiah($o->total) ?></th></tr>
				</table>
			</div>
			<?php if ($o->catatan): ?><div class="card-footer small"><b>Catatan:</b> <?= html_escape($o->catatan) ?></div><?php endif; ?>
		</div>
	</div>

	<div class="col-lg-5">
		<?php if ($o->status === 'ditolak'): ?>
			<div class="alert alert-danger"><b>Pembayaran ditolak:</b> <?= html_escape($o->alasan_tolak) ?><br>Silakan unggah ulang bukti yang benar.</div>
		<?php endif; ?>

		<?php if ($boleh_upload): ?>
			<div class="card card-warning card-outline">
				<div class="card-header"><h3 class="card-title"><i class="fas fa-wallet mr-1"></i> Pembayaran</h3></div>
				<div class="card-body">
					<p class="mb-1">Transfer tepat <b class="h5"><?= rupiah($o->total) ?></b> ke:</p>
					<p class="bg-light p-2 rounded"><i class="fas fa-university mr-1"></i> <?= html_escape($o->rekening) ?></p>
					<p class="small text-muted">Bayar sebelum PO ditutup (<?= tgl($o->ditutup_at, TRUE) ?>).</p>
					<?= form_open_multipart('pesanan/upload/'.$o->kode) ?>
						<div class="form-group">
							<label for="bukti">Unggah bukti transfer</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="bukti" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required
									onchange="this.nextElementSibling.textContent = this.files[0] ? this.files[0].name : 'Pilih file'">
								<label class="custom-file-label" for="bukti">Pilih file</label>
							</div>
							<small class="text-muted">JPG/PNG/PDF, maks. 2 MB.</small>
						</div>
						<button type="submit" class="btn btn-warning btn-block"><i class="fas fa-upload mr-1"></i> Kirim bukti</button>
					<?= form_close() ?>
				</div>
				<?php if ($o->status === 'menunggu_pembayaran'): ?>
					<div class="card-footer text-right">
						<?= form_open('pesanan/batal/'.$o->kode, array('onsubmit' => "return confirm('Batalkan pesanan ini?')")) ?>
							<button type="submit" class="btn btn-sm btn-link text-danger">Batalkan pesanan</button>
						<?= form_close() ?>
					</div>
				<?php endif; ?>
			</div>
		<?php elseif ($o->bukti_bayar): ?>
			<div class="card">
				<div class="card-header"><h3 class="card-title">Bukti pembayaran</h3></div>
				<div class="card-body text-center">
					<a href="<?= site_url('pesanan/bukti/'.$o->kode) ?>" target="_blank"><img class="img-fluid bukti-preview" src="<?= site_url('pesanan/bukti/'.$o->kode) ?>" alt="Bukti pembayaran" onerror="this.replaceWith('Lihat file bukti')"></a>
					<p class="small text-muted mt-2 mb-0">Dikirim <?= tgl($o->dibayar_at, TRUE) ?><?= $o->diverifikasi_at ? ' &middot; diverifikasi '.tgl($o->diverifikasi_at, TRUE) : '' ?></p>
				</div>
			</div>
		<?php endif; ?>

		<?php if (in_array($o->status, array('lunas', 'siap_diambil'), TRUE)): ?>
			<div class="callout callout-success">
				<i class="fas fa-box-open mr-1"></i>
				<?= $o->status === 'siap_diambil' ? '<b>Barang siap diambil</b> di sekretariat himpunan. Tunjukkan kode pesanan saat mengambil.'
					: 'Pembayaran terverifikasi. Barang sedang diproduksi, estimasi bisa diambil '.tgl($o->estimasi_ambil).'.' ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<a href="<?= site_url('pesanan') ?>"><i class="fas fa-arrow-left"></i> Semua pesanan</a>
