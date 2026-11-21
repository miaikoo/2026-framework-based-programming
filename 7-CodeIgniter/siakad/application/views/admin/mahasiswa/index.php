<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($mahasiswa) ?> mahasiswa terdaftar</h3>
		<div class="card-tools">
			<a href="<?= site_url('admin/mahasiswa/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Mahasiswa</a>
		</div>
	</div>
	<div class="card-body p-0">
		<table class="table table-striped table-hover mb-0">
			<thead><tr><th>NIM</th><th>Nama</th><th>L/P</th><th>Program Studi</th><th>Angkatan</th><th>Email</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($mahasiswa as $m): ?>
					<tr>
						<td><?= $m->nim ?></td>
						<td><?= html_escape($m->nama) ?></td>
						<td><?= $m->gender ?></td>
						<td><?= html_escape($m->prodi) ?></td>
						<td><?= $m->angkatan ?></td>
						<td><?= html_escape($m->email) ?></td>
						<td class="text-right text-nowrap">
							<a href="<?= site_url('admin/mahasiswa/edit/'.$m->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/mahasiswa/hapus/'.$m->id) ?>" data-hapus-nama="<?= html_escape($m->nama) ?>"
								data-hapus-catatan="Akun login dan seluruh data presensinya ikut terhapus."><i class="fas fa-trash"></i> Hapus</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
