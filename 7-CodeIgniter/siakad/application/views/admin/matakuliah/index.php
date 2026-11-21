<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($matakuliah) ?> mata kuliah &middot; <?= array_sum(array_column($matakuliah, 'sks')) ?> SKS</h3>
		<div class="card-tools"><a href="<?= site_url('admin/matakuliah/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Mata Kuliah</a></div>
	</div>
	<div class="card-body p-0">
		<table class="table table-striped table-hover mb-0">
			<thead><tr><th>Kode</th><th>Nama Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Semester</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($matakuliah as $m): ?>
					<tr>
						<td><code><?= $m->kode ?></code></td>
						<td><?= html_escape($m->nama) ?></td>
						<td class="text-center"><?= $m->sks ?></td>
						<td class="text-center"><?= $m->semester ?></td>
						<td class="text-right text-nowrap">
							<a href="<?= site_url('admin/matakuliah/edit/'.$m->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/matakuliah/hapus/'.$m->id) ?>" data-hapus-nama="<?= html_escape($m->nama) ?>"><i class="fas fa-trash"></i> Hapus</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
