<div class="d-flex justify-content-between align-items-center mb-2">
	<a href="<?= site_url('admin/pesanan') ?>"><i class="fas fa-arrow-left"></i> Daftar pesanan</a>
	<span class="h5 mb-0"><?= badge_status($o->status) ?></span>
</div>
<div class="card"><div class="card-body py-2"><?php $this->load->view('pesanan/_timeline'); ?></div></div>

<div class="row">
	<div class="col-lg-7">
		<div class="card">
			<div class="card-header"><h3 class="card-title"><?= html_escape($o->pembeli) ?> &middot; <?= html_escape($o->batch) ?></h3></div>
			<div class="card-body pb-0">
				<p class="mb-2 small">
					<i class="fas fa-envelope mr-1 text-muted"></i><?= html_escape($o->email) ?> &nbsp;
					<i class="fab fa-whatsapp mr-1 text-success"></i><?= html_escape($o->no_wa) ?> &nbsp;
					<i class="far fa-clock mr-1 text-muted"></i><?= tgl($o->created_at, TRUE) ?>
				</p>
			</div>
			<table class="table mb-0">
				<?php foreach ($items as $it): ?>
					<tr>
						<td><img class="thumb mr-2" src="<?= base_url('upload/produk/'.$it->gambar) ?>" alt=""><?= html_escape($it->nama) ?><?= $it->ukuran ? ' <span class="badge badge-secondary">'.$it->ukuran.'</span>' : '' ?></td>
						<td class="align-middle"><?= $it->qty ?> &times; <?= rupiah($it->harga) ?></td>
						<td class="align-middle text-right"><?= rupiah($it->subtotal) ?></td>
					</tr>
				<?php endforeach; ?>
				<tr class="bg-light"><th colspan="2">Total yang harus ditransfer</th><th class="text-right h5 mb-0"><?= rupiah($o->total) ?></th></tr>
			</table>
			<?php if ($o->catatan): ?><div class="card-footer small"><b>Catatan pembeli:</b> <?= html_escape($o->catatan) ?></div><?php endif; ?>
		</div>
	</div>

	<div class="col-lg-5">
		<div class="card card-outline card-info">
			<div class="card-header"><h3 class="card-title">Bukti pembayaran</h3></div>
			<div class="card-body text-center">
				<?php if ($o->bukti_bayar): ?>
					<a href="<?= site_url('admin/pesanan/bukti/'.$o->kode) ?>" target="_blank">
						<img class="img-fluid bukti-preview" src="<?= site_url('admin/pesanan/bukti/'.$o->kode) ?>" alt="Bukti" onerror="this.replaceWith('Buka file bukti (PDF)')">
					</a>
					<p class="small text-muted mt-2 mb-0">Diunggah <?= tgl($o->dibayar_at, TRUE) ?></p>
				<?php else: ?>
					<p class="text-muted mb-0">Pembeli belum mengunggah bukti.</p>
				<?php endif; ?>
				<?php if ($o->alasan_tolak): ?><div class="alert alert-danger small mt-2 mb-0 text-left">Ditolak: <?= html_escape($o->alasan_tolak) ?></div><?php endif; ?>
			</div>

			<?php if ($o->status === 'menunggu_verifikasi'): ?>
				<div class="card-footer">
					<?= form_open('admin/pesanan/ubah_status/'.$o->kode) ?>
						<input type="hidden" name="aksi" value="verifikasi">
						<button type="submit" class="btn btn-success btn-block mb-2"><i class="fas fa-check mr-1"></i> Nominal sesuai, verifikasi</button>
					<?= form_close() ?>
					<?= form_open('admin/pesanan/ubah_status/'.$o->kode) ?>
						<input type="hidden" name="aksi" value="tolak">
						<div class="input-group">
							<input name="alasan_tolak" class="form-control" placeholder="Alasan penolakan" maxlength="255">
							<div class="input-group-append"><button type="submit" class="btn btn-outline-danger">Tolak</button></div>
						</div>
					<?= form_close() ?>
				</div>
			<?php elseif ($o->status === 'lunas'): ?>
				<div class="card-footer">
					<?= form_open('admin/pesanan/ubah_status/'.$o->kode) ?>
						<input type="hidden" name="aksi" value="siap">
						<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-box-open mr-1"></i> Tandai siap diambil</button>
					<?= form_close() ?>
				</div>
			<?php elseif ($o->status === 'siap_diambil'): ?>
				<div class="card-footer">
					<?= form_open('admin/pesanan/ubah_status/'.$o->kode) ?>
						<input type="hidden" name="aksi" value="selesai">
						<button type="submit" class="btn btn-secondary btn-block"><i class="fas fa-flag-checkered mr-1"></i> Sudah diambil pembeli</button>
					<?= form_close() ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
