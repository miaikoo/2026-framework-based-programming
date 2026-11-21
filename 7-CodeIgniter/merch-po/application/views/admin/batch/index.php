<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($batches) ?> batch preorder</h3>
		<div class="card-tools"><a href="<?= site_url('admin/batch/tambah') ?>" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Buat batch</a></div>
	</div>
	<div class="card-body p-0">
		<table class="table table-hover mb-0">
			<thead><tr><th>Nama</th><th>Periode PO</th><th>Estimasi ambil</th><th class="text-center">Produk</th><th class="text-center">Pesanan</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($batches as $b): ?>
					<tr>
						<td><b><?= html_escape($b->nama) ?></b></td>
						<td class="small"><?= tgl($b->dibuka_at, TRUE) ?><br>s.d. <?= tgl($b->ditutup_at, TRUE) ?></td>
						<td><?= tgl($b->estimasi_ambil) ?></td>
						<td class="text-center"><?= $b->jumlah_produk ?></td>
						<td class="text-center"><?= $b->jumlah_pesanan ?></td>
						<td><?= badge_batch(Batch_model::status($b)) ?></td>
						<td class="text-right text-nowrap">
							<a href="<?= site_url('admin/rekap/index/'.$b->id) ?>" class="btn btn-xs btn-outline-success"><i class="fas fa-clipboard-list"></i> Rekap</a>
							<a href="<?= site_url('admin/batch/edit/'.$b->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/batch/hapus/'.$b->id) ?>" data-hapus-nama="<?= html_escape($b->nama) ?>"><i class="fas fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
