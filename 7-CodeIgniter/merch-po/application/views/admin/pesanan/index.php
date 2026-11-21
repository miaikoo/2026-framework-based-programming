<div class="mb-3">
	<a href="<?= site_url('admin/pesanan') ?>" class="btn btn-sm <?= ! $f_status ? 'btn-dark' : 'btn-outline-dark' ?>">Semua <span class="badge badge-light"><?= array_sum($per_status) ?></span></a>
	<?php foreach (Pesanan_model::STATUS as $key => $s): ?>
		<a href="<?= site_url('admin/pesanan?status='.$key.($f_batch ? '&batch='.(int) $f_batch : '')) ?>"
			class="btn btn-sm mb-1 <?= $f_status === $key ? 'btn-'.$s[1] : 'btn-outline-'.$s[1] ?>"><?= $s[0] ?> <span class="badge badge-light"><?= $per_status[$key] ?></span></a>
	<?php endforeach; ?>
</div>

<div class="card">
	<div class="card-header">
		<form method="get" class="form-inline">
			<?php if ($f_status): ?><input type="hidden" name="status" value="<?= html_escape($f_status) ?>"><?php endif; ?>
			<label class="mr-2 small" for="fbatch">Batch</label>
			<?= form_dropdown('batch', array('' => 'Semua batch') + $opsi_batch, $f_batch, 'id="fbatch" class="custom-select custom-select-sm" onchange="this.form.submit()"') ?>
		</form>
	</div>
	<div class="card-body p-0">
		<table class="table table-hover mb-0">
			<thead><tr><th>Kode</th><th>Pembeli</th><th>Batch</th><th>Tanggal</th><th class="text-center">Barang</th><th class="text-right">Total</th><th>Status</th><th></th></tr></thead>
			<tbody>
				<?php foreach ($pesanan as $o): ?>
					<tr>
						<td><b><?= $o->kode ?></b></td>
						<td><?= html_escape($o->pembeli) ?><br><small class="text-muted"><?= html_escape($o->no_wa) ?></small></td>
						<td class="small"><?= html_escape($o->batch) ?></td>
						<td class="small"><?= tgl($o->created_at, TRUE) ?></td>
						<td class="text-center"><?= $o->jumlah_barang ?></td>
						<td class="text-right"><?= rupiah($o->total) ?></td>
						<td><?= badge_status($o->status) ?></td>
						<td><a href="<?= site_url('admin/pesanan/detail/'.$o->kode) ?>" class="btn btn-xs btn-outline-primary">Detail</a></td>
					</tr>
				<?php endforeach; ?>
				<?php if ( ! $pesanan): ?><tr><td colspan="8" class="text-center text-muted p-4">Tidak ada pesanan.</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
