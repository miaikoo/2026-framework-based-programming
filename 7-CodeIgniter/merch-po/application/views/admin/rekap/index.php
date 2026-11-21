<div class="d-flex flex-wrap align-items-center mb-3">
	<div class="btn-group mr-3 mb-2">
		<?php foreach ($batches as $b): ?>
			<a href="<?= site_url('admin/rekap/index/'.$b->id) ?>" class="btn btn-sm <?= $b->id === $batch->id ? 'btn-warning' : 'btn-outline-secondary' ?>"><?= html_escape($b->nama) ?></a>
		<?php endforeach; ?>
	</div>
	<a href="<?= site_url('admin/rekap/csv/'.$batch->id) ?>" class="btn btn-sm btn-success mb-2 ml-auto"><i class="fas fa-file-csv mr-1"></i> Unduh CSV untuk vendor</a>
</div>

<div class="row">
	<div class="col-lg-7">
		<div class="card card-outline card-warning">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-industry mr-1"></i> Jumlah produksi &middot; <?= html_escape($batch->nama) ?></h3>
				<div class="card-tools"><?= badge_batch(Batch_model::status($batch)) ?></div>
			</div>
			<div class="card-body p-0">
				<table class="table table-bordered rekap mb-0">
					<thead class="thead-light">
						<tr><th>Produk</th><?php foreach (Produk_model::UKURAN as $u): ?><th><?= $u ?></th><?php endforeach; ?><th>Total</th></tr>
					</thead>
					<tbody>
						<?php $total_ukuran = array_fill_keys(Produk_model::UKURAN, 0); ?>
						<?php foreach ($rekap as $r): ?>
							<tr>
								<td><?= html_escape($r->nama) ?></td>
								<?php foreach (Produk_model::UKURAN as $u): ?>
									<?php if ($r->pakai_ukuran) $total_ukuran[$u] += $r->per_ukuran[$u]; ?>
									<td class="<?= $r->pakai_ukuran && $r->per_ukuran[$u] ? 'font-weight-bold' : 'text-muted' ?>"><?= $r->pakai_ukuran ? $r->per_ukuran[$u] : '–' ?></td>
								<?php endforeach; ?>
								<td class="font-weight-bold bg-light"><?= $r->total ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $rekap): ?><tr><td colspan="7" class="text-center text-muted p-3">Belum ada pesanan yang lunas.</td></tr><?php endif; ?>
					</tbody>
					<?php if ($rekap): ?>
						<tfoot class="bg-light"><tr><th>Total pakaian per ukuran</th><?php foreach ($total_ukuran as $n): ?><th><?= $n ?></th><?php endforeach; ?><th></th></tr></tfoot>
					<?php endif; ?>
				</table>
			</div>
			<div class="card-footer small text-muted">
				Hanya menghitung pesanan berstatus lunas, siap diambil, dan selesai. Dana masuk: <b class="text-dark"><?= rupiah($pendapatan) ?></b>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-people-carry mr-1"></i> Daftar pengambilan (<?= count($ambil) ?>)</h3></div>
			<div class="card-body p-0" style="max-height:460px;overflow:auto">
				<table class="table table-sm mb-0">
					<?php foreach ($ambil as $a): ?>
						<tr>
							<td><b><?= html_escape($a->nama) ?></b> <small class="text-muted"><?= $a->kode ?></small><br><small><?= html_escape($a->barang) ?></small></td>
							<td class="text-right align-middle"><?= badge_status($a->status) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	</div>
</div>
