<div class="card card-primary card-outline" style="max-width:640px">
	<?= form_open($row ? 'admin/dosen/edit/'.$row->id : 'admin/dosen/tambah') ?>
		<div class="card-body">
			<div class="form-group">
				<label for="nidn">NIDN</label>
				<input id="nidn" name="nidn" class="form-control<?= invalid('nidn') ?>" value="<?= nilai('nidn', $row) ?>" maxlength="10">
				<?= pesan_error('nidn') ?>
			</div>
			<div class="form-group">
				<label for="nama">Nama & gelar</label>
				<input id="nama" name="nama" class="form-control<?= invalid('nama') ?>" value="<?= nilai('nama', $row) ?>">
				<?= pesan_error('nama') ?>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input id="email" name="email" type="email" class="form-control<?= invalid('email') ?>" value="<?= nilai('email', $row) ?>">
				<?= pesan_error('email') ?>
			</div>
			<?php if ( ! $row): ?>
				<p class="text-muted small mb-0"><i class="fas fa-info-circle"></i> Akun login dibuat otomatis dengan username dan password awal = NIDN.</p>
			<?php endif; ?>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/dosen') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
