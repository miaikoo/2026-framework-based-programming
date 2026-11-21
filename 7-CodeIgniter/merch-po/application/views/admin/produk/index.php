<div class="card">
	<div class="card-header">
		<h3 class="card-title"><?= count($produk) ?> produk</h3>
		<div class="card-tools"><a href="<?= site_url('admin/produk/tambah') ?>" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Tambah produk</a></div>
	</div>
	<div class="card-body p-0">
		<table class="table table-hover mb-0">
			<thead><tr><th>Produk</th><th>Batch</th><th class="text-right">Harga</th><th>Ukuran</th><th class="text-center">Terpesan / kuota</th><th class="text-right">Aksi</th></tr></thead>
			<tbody>
				<?php foreach ($produk as $p): ?>
					<tr>
						<td><img class="thumb mr-2" src="<?= base_url('upload/produk/'.$p->gambar) ?>" alt=""><?= html_escape($p->nama) ?></td>
						<td class="align-middle small"><?= html_escape($p->batch) ?></td>
						<td class="align-middle text-right"><?= rupiah($p->harga) ?></td>
						<td class="align-middle"><?= $p->pakai_ukuran ? 'S–XXL' : '<span class="text-muted">-</span>' ?></td>
						<td class="align-middle text-center"><?= $p->terpesan ?> / <?= $p->kuota === NULL ? '∞' : $p->kuota ?></td>
						<td class="align-middle text-right text-nowrap">
							<a href="<?= site_url('produk/'.$p->id) ?>" class="btn btn-xs btn-outline-secondary" target="_blank"><i class="fas fa-eye"></i></a>
							<a href="<?= site_url('admin/produk/edit/'.$p->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
							<button type="button" class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
								data-hapus-url="<?= site_url('admin/produk/hapus/'.$p->id) ?>" data-hapus-nama="<?= html_escape($p->nama) ?>"><i class="fas fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
