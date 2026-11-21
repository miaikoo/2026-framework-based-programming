<?php $this->load->view('admin/_partials/head'); ?>
<?php $this->load->view('admin/_partials/navbar'); ?>
<div id="layoutSidenav">
	<?php $this->load->view('admin/_partials/sidebar'); ?>
	<div id="layoutSidenav_content">
		<main>
			<div class="container-fluid px-4">
				<div class="d-flex justify-content-between align-items-center mt-4">
					<h1 class="mb-0">Produk</h1>
					<a href="<?= site_url('admin/products/add') ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Produk</a>
				</div>
				<?php $this->load->view('admin/_partials/breadcrumb'); ?>
				<?php $this->load->view('admin/_partials/alert'); ?>

				<div class="card mb-4">
					<div class="card-header"><i class="fas fa-table me-1"></i> <?= count($products) ?> produk</div>
					<div class="card-body">
						<table class="table table-hover align-middle datatable">
							<thead>
								<tr>
									<th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Deskripsi</th><th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($products as $p): ?>
									<tr>
										<td><img class="thumb" src="<?= base_url('upload/product/'.$p->image) ?>" alt="<?= html_escape($p->name) ?>"></td>
										<td class="fw-semibold"><?= html_escape($p->name) ?></td>
										<td><span class="badge <?= $p->category === 'Impor' ? 'bg-info text-dark' : 'bg-success' ?>"><?= $p->category ?></span></td>
										<td><?= rupiah($p->price) ?> <span class="text-muted small">/ <?= $p->unit ?></span></td>
										<td class="<?= $p->stock < 20 ? 'text-danger fw-bold' : '' ?>"><?= $p->stock ?></td>
										<td class="small text-muted"><?= html_escape(character_limiter((string) $p->description, 45, '…')) ?></td>
										<td class="text-nowrap">
											<a href="<?= site_url('admin/products/edit/'.$p->product_id) ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
											<?php if ($user->role === 'admin'): ?>
												<button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
													data-id="<?= $p->product_id ?>" data-name="<?= html_escape($p->name) ?>"><i class="fas fa-trash"></i> Hapus</button>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>
		<?php $this->load->view('admin/_partials/footer'); ?>
	</div>
</div>

<!-- Modal konfirmasi hapus: form POST + token CSRF dari form_open() -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<?= form_open('admin/products/delete', array('class' => 'modal-content')) ?>
			<div class="modal-header">
				<h5 class="modal-title"><i class="fas fa-triangle-exclamation text-danger me-1"></i> Hapus produk?</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
			</div>
			<div class="modal-body">
				Produk <b id="hapusNama"></b> beserta gambarnya akan dihapus permanen.
				<input type="hidden" name="product_id" id="hapusId">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-danger">Ya, hapus</button>
			</div>
		<?= form_close() ?>
	</div>
</div>

<?php $this->load->view('admin/_partials/js'); ?>
<script>
	document.getElementById('modalHapus').addEventListener('show.bs.modal', function (e) {
		document.getElementById('hapusId').value = e.relatedTarget.dataset.id;
		document.getElementById('hapusNama').textContent = e.relatedTarget.dataset.name;
	});
</script>
</body>
</html>
