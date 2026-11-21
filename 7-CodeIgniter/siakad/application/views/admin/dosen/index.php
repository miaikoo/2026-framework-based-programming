<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($dosen) ?> dosen</h3>
		<div class="card-tools"><a href="<?= site_url('admin/dosen/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Dosen</a></div>
	</div>
	<div class="card-body p-0">
		<table class="table table-striped table-hover mb-0">
			<thead><tr><th>NIDN</th><th>Nama</th><th>Email</th><th class="text-center">Kelas diampu</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($dosen as $d): ?>
					<tr>
						<td><?= $d->nidn ?></td>
						<td><?= html_escape($d->nama) ?></td>
						<td><?= html_escape($d->email) ?></td>
						<td class="text-center"><span class="badge badge-info"><?= $d->jumlah_kelas ?></span></td>
						<td class="text-right text-nowrap">
							<a href="<?= site_url('admin/dosen/edit/'.$d->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/dosen/hapus/'.$d->id) ?>" data-hapus-nama="<?= html_escape($d->nama) ?>"><i class="fas fa-trash"></i> Hapus</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
