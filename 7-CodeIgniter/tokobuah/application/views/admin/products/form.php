<?php
// satu view untuk tambah & edit. set_value() mengisi ulang input saat validasi gagal.
$is_edit = ($mode === 'edit');
$val = function ($field, $default = '') use ($product) {
	return set_value($field, $product ? $product->$field : $default);
};
$err = function ($field) {
	return form_error($field) ? ' is-invalid' : '';
};
$action = $is_edit ? 'admin/products/edit/'.$product->product_id : 'admin/products/add';
?>
<?php $this->load->view('admin/_partials/head'); ?>
<?php $this->load->view('admin/_partials/navbar'); ?>
<div id="layoutSidenav">
	<?php $this->load->view('admin/_partials/sidebar'); ?>
	<div id="layoutSidenav_content">
		<main>
			<div class="container-fluid px-4">
				<h1 class="mt-4"><?= $title ?></h1>
				<?php $this->load->view('admin/_partials/breadcrumb'); ?>

				<?php if (validation_errors() || $upload_error): ?>
					<div class="alert alert-danger">
						<b>Data belum bisa disimpan:</b>
						<ul class="mb-0"><?= validation_errors('<li>', '</li>') ?><?= $upload_error ? '<li>Gambar: '.$upload_error.'</li>' : '' ?></ul>
					</div>
				<?php endif; ?>

				<div class="card mb-4">
					<div class="card-body">
						<?= form_open_multipart($action) ?>
							<div class="row g-4">
								<div class="col-lg-8">
									<div class="mb-3">
										<label class="form-label" for="name">Nama produk *</label>
										<input class="form-control<?= $err('name') ?>" id="name" name="name" value="<?= $val('name') ?>" placeholder="mis. Apel Fuji" autofocus>
									</div>
									<div class="row">
										<div class="col-md-6 mb-3">
											<label class="form-label" for="price">Harga (Rp) *</label>
											<input class="form-control<?= $err('price') ?>" id="price" name="price" type="number" min="1" value="<?= $val('price') ?>">
										</div>
										<div class="col-md-3 mb-3">
											<label class="form-label" for="unit">Satuan *</label>
											<select class="form-select<?= $err('unit') ?>" id="unit" name="unit">
												<?php foreach (array('kg', 'buah', 'sisir', 'pack') as $u): ?>
													<option value="<?= $u ?>" <?= set_select('unit', $u, $val('unit', 'kg') === $u) ?>><?= $u ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-md-3 mb-3">
											<label class="form-label" for="stock">Stok *</label>
											<input class="form-control<?= $err('stock') ?>" id="stock" name="stock" type="number" min="0" value="<?= $val('stock', '0') ?>">
										</div>
									</div>
									<div class="mb-3">
										<label class="form-label d-block">Kategori *</label>
										<?php foreach (array('Lokal', 'Impor') as $c): ?>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="category" id="cat<?= $c ?>" value="<?= $c ?>" <?= set_radio('category', $c, $val('category', 'Lokal') === $c) ?>>
												<label class="form-check-label" for="cat<?= $c ?>"><?= $c ?></label>
											</div>
										<?php endforeach; ?>
									</div>
									<div class="mb-3">
										<label class="form-label" for="description">Deskripsi</label>
										<textarea class="form-control<?= $err('description') ?>" id="description" name="description" rows="4" maxlength="500"><?= $val('description') ?></textarea>
									</div>
								</div>

								<div class="col-lg-4">
									<label class="form-label" for="image">Gambar produk</label>
									<div class="border rounded p-2 mb-2 text-center bg-light">
										<img id="preview" class="img-fluid rounded" style="max-height:220px"
											src="<?= base_url('upload/product/'.($product ? $product->image : 'default.png')) ?>" alt="Pratinjau gambar">
									</div>
									<input class="form-control" type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
									<div class="form-text">JPG/PNG/WEBP, maks. 2 MB.<?= $is_edit ? ' Kosongkan jika tidak ingin mengganti gambar.' : '' ?></div>
								</div>
							</div>

							<hr>
							<button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk me-1"></i> Simpan</button>
							<a href="<?= site_url('admin/products') ?>" class="btn btn-outline-secondary">Batal</a>
						<?= form_close() ?>
					</div>
				</div>
			</div>
		</main>
		<?php $this->load->view('admin/_partials/footer'); ?>
	</div>
</div>
<?php $this->load->view('admin/_partials/js'); ?>
<script>
	// pratinjau gambar sebelum diupload
	document.getElementById('image').addEventListener('change', function () {
		if (this.files[0]) document.getElementById('preview').src = URL.createObjectURL(this.files[0]);
	});
</script>
</body>
</html>
