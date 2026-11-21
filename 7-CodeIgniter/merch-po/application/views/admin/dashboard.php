<div class="row">
	<div class="col-lg-3 col-6">
		<div class="small-box bg-info">
			<div class="inner"><h3><?= $per_status['menunggu_verifikasi'] ?></h3><p>Perlu verifikasi</p></div>
			<div class="icon"><i class="fas fa-hourglass-half"></i></div>
			<a href="<?= site_url('admin/pesanan?status=menunggu_verifikasi') ?>" class="small-box-footer">Periksa <i class="fas fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-6">
		<div class="small-box bg-warning">
			<div class="inner"><h3><?= $per_status['menunggu_pembayaran'] ?></h3><p>Belum bayar</p></div>
			<div class="icon"><i class="fas fa-wallet"></i></div>
			<a href="<?= site_url('admin/pesanan?status=menunggu_pembayaran') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-6">
		<div class="small-box bg-primary">
			<div class="inner"><h3><?= $per_status['lunas'] + $per_status['siap_diambil'] ?></h3><p>Lunas / siap diambil</p></div>
			<div class="icon"><i class="fas fa-check"></i></div>
			<a href="<?= site_url('admin/pesanan?status=lunas') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-6">
		<div class="small-box bg-success">
			<div class="inner"><h3 style="font-size:1.9rem"><?= rupiah($pendapatan) ?></h3><p>Dana masuk (batch aktif)</p></div>
			<div class="icon"><i class="fas fa-coins"></i></div>
			<a href="<?= site_url('admin/rekap') ?>" class="small-box-footer">Rekap <i class="fas fa-arrow-circle-right"></i></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Terjual (pesanan sah)</h3>
				<?php if ($batch): ?><div class="card-tools"><span class="badge badge-success"><?= html_escape($batch->nama) ?></span></div><?php endif; ?>
			</div>
			<div class="card-body">
				<?php if ($batch): ?><canvas id="grafikTerjual" height="210"></canvas><?php else: ?><p class="text-muted mb-0">Tidak ada batch yang sedang dibuka.</p><?php endif; ?>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="card card-outline card-info">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-hourglass-half mr-1"></i> Menunggu verifikasi pembayaran</h3></div>
			<div class="card-body p-0">
				<table class="table table-sm table-hover mb-0">
					<thead><tr><th>Kode</th><th>Pembeli</th><th class="text-right">Total</th><th>Dibayar</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($perlu_cek as $o): ?>
							<tr>
								<td class="text-nowrap"><?= $o->kode ?></td><td><?= html_escape($o->pembeli) ?></td>
								<td class="text-right text-nowrap"><?= rupiah($o->total) ?></td><td class="small"><?= tgl($o->dibayar_at, TRUE) ?></td>
								<td><a href="<?= site_url('admin/pesanan/detail/'.$o->kode) ?>" class="btn btn-xs btn-info">Cek</a></td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $perlu_cek): ?><tr><td colspan="5" class="text-center text-muted p-3">Semua pembayaran sudah diperiksa 🎉</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php if ($batch): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
	new Chart(document.getElementById('grafikTerjual'), {
		type: 'bar',
		data: {
			labels: <?= json_encode(array_column($terjual, 'nama')) ?>,
			datasets: [{ label: 'pcs', data: <?= json_encode(array_map('intval', array_column($terjual, 'qty'))) ?>, backgroundColor: '#7c3aed', borderRadius: 4 }]
		},
		options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
	});
</script>
<?php endif; ?>
