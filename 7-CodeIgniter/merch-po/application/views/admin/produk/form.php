<div class="card card-warning card-outline">
	<?= form_open_multipart($row ? 'admin/produk/edit/'.$row->id : 'admin/produk/tambah') ?>
		<div class="card-body">
			<?php if ($upload_error): ?><div class="alert alert-danger">Gambar: <?= $upload_error ?></div><?php endif; ?>
			<div class="row">
				<div class="col-md-8">
					<div class="form-row">
						<div class="form-group col-md-7">
							<label for="nama">Nama produk</label>
							<input id="nama" name="nama" class="form-control<?= invalid('nama') ?>" value="<?= nilai('nama', $row) ?>">
							<?= pesan_error('nama') ?>
						</div>
						<div class="form-group col-md-5">
							<label for="batch_id">Batch PO</label>
							<?= form_dropdown('batch_id', $opsi_batch, set_value('batch_id', $row ? $row->batch_id : ''), 'id="batch_id" class="custom-select'.invalid('batch_id').'"') ?>
							<?= pesan_error('batch_id') ?>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="harga">Harga (Rp)</label>
							<input id="harga" name="harga" type="number" min="1" class="form-control<?= invalid('harga') ?>" value="<?= nilai('harga', $row) ?>">
							<?= pesan_error('harga') ?>
						</div>
						<div class="form-group col-md-4">
							<label for="kuota">Kuota <small class="text-muted">(kosongkan = tanpa batas)</small></label>
							<input id="kuota" name="kuota" type="number" min="1" class="form-control<?= invalid('kuota') ?>" value="<?= nilai('kuota', $row) ?>">
							<?= pesan_error('kuota') ?>
						</div>
						<div class="form-group col-md-4">
							<label class="d-block">Pilihan ukuran</label>
							<div class="custom-control custom-switch mt-2">
								<input type="checkbox" class="custom-control-input" id="pakai_ukuran" name="pakai_ukuran" value="1"
									<?= set_checkbox('pakai_ukuran', '1', $row ? (bool) $row->pakai_ukuran : FALSE) ?>>
								<label class="custom-control-label" for="pakai_ukuran">Wajib pilih S–XXL</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="deskripsi">Deskripsi</label>
						<textarea id="deskripsi" name="deskripsi" rows="4" class="form-control"><?= nilai('deskripsi', $row) ?></textarea>
					</div>
				</div>
				<div class="col-md-4">
					<label for="gambar">Foto produk</label>
					<div class="text-center bg-light border rounded p-2 mb-2">
						<img id="preview" class="img-fluid rounded" style="max-height:220px" src="<?= base_url('upload/produk/'.($row ? $row->gambar : 'default.png')) ?>" alt="">
					</div>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
						<label class="custom-file-label" for="gambar">Pilih gambar</label>
					</div>
					<small class="text-muted">JPG/PNG/WEBP maks. 2 MB<?= $row ? ', kosongkan bila tidak diganti' : '' ?>.</small>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/produk') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
<script>
	document.getElementById('gambar').addEventListener('change', function () {
		if (!this.files[0]) return;
		this.nextElementSibling.textContent = this.files[0].name;
		document.getElementById('preview').src = URL.createObjectURL(this.files[0]);
	});
</script>
