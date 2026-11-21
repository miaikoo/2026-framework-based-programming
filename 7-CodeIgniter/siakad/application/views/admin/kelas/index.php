<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($kelas) ?> kelas dibuka</h3>
		<div class="card-tools"><a href="<?= site_url('admin/kelas/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buka Kelas</a></div>
	</div>
	<div class="card-body p-0">
		<table class="table table-hover mb-0">
			<thead><tr><th>Mata Kuliah</th><th>Kelas</th><th>Dosen</th><th>Jadwal</th><th>Ruang</th><th class="text-center">Peserta</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($kelas as $k): ?>
					<tr>
						<td><code><?= $k->kode ?></code> <?= html_escape($k->matakuliah) ?> <small class="text-muted">(<?= $k->sks ?> SKS)</small></td>
						<td><?= $k->nama_kelas ?></td>
						<td><?= html_escape($k->dosen) ?></td>
						<td class="text-nowrap"><?= $k->hari ?>, <?= jam($k->jam_mulai) ?>–<?= jam($k->jam_selesai) ?></td>
						<td><?= html_escape($k->ruang) ?></td>
						<td class="text-center"><span class="badge badge-primary"><?= $k->jumlah_peserta ?></span></td>
						<td class="text-right text-nowrap">
							<a href="<?= site_url('admin/kelas/peserta/'.$k->id) ?>" class="btn btn-xs btn-outline-success"><i class="fas fa-users"></i> Peserta</a>
							<a href="<?= site_url('admin/kelas/edit/'.$k->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/kelas/hapus/'.$k->id) ?>" data-hapus-nama="<?= html_escape($k->matakuliah.' kelas '.$k->nama_kelas) ?>"
								data-hapus-catatan="Seluruh pertemuan dan presensi kelas ini ikut terhapus."><i class="fas fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
