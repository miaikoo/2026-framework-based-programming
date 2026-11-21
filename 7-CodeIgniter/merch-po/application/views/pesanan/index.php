<h3 class="mb-3"><i class="fas fa-receipt mr-1"></i> Pesanan Saya</h3>
<div class="card">
	<div class="card-body p-0">
		<table class="table table-hover mb-0">
			<thead><tr><th>Kode</th><th>Batch</th><th>Tanggal</th><th class="text-center">Barang</th><th class="text-right">Total</th><th>Status</th><th></th></tr></thead>
			<tbody>
				<?php foreach ($pesanan as $o): ?>
					<tr>
						<td><b><?= $o->kode ?></b></td>
						<td><?= html_escape($o->batch) ?></td>
						<td><?= tgl($o->created_at, TRUE) ?></td>
						<td class="text-center"><?= $o->jumlah_barang ?></td>
						<td class="text-right"><?= rupiah($o->total) ?></td>
						<td><?= badge_status($o->status) ?></td>
						<td class="text-right"><a href="<?= site_url('pesanan/detail/'.$o->kode) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
					</tr>
				<?php endforeach; ?>
				<?php if ( ! $pesanan): ?><tr><td colspan="7" class="text-center text-muted p-4">Belum ada pesanan. <a href="<?= base_url() ?>">Mulai belanja</a></td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
