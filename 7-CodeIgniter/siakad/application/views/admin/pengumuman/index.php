<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($pengumuman) ?> pengumuman</h3>
		<div class="card-tools"><a href="<?= site_url('admin/pengumuman/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Pengumuman</a></div>
	</div>
	<div class="card-body p-0">
		<table class="table table-hover mb-0">
			<thead><tr><th style="width:150px">Tanggal</th><th>Judul & isi</th><th>Untuk</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php $warna = array('semua' => 'primary', 'dosen' => 'success', 'mahasiswa' => 'info'); ?>
				<?php foreach ($pengumuman as $p): ?>
					<tr>
						<td class="text-nowrap"><?= tanggal_indo($p->created_at) ?></td>
						<td><b><?= html_escape($p->judul) ?></b><br><small class="text-muted"><?= html_escape(mb_strimwidth($p->isi, 0, 120, '…')) ?></small></td>
						<td><span class="badge badge-<?= $warna[$p->untuk] ?>"><?= $p->untuk ?></span></td>
						<td class="text-right text-nowrap">
							<a href="<?= site_url('admin/pengumuman/edit/'.$p->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/pengumuman/hapus/'.$p->id) ?>" data-hapus-nama="<?= html_escape($p->judul) ?>"><i class="fas fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
