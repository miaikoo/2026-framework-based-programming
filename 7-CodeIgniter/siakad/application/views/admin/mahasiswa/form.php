<div class="card card-primary card-outline" style="max-width:760px">
	<?= form_open($row ? 'admin/mahasiswa/edit/'.$row->id : 'admin/mahasiswa/tambah') ?>
		<div class="card-body">
			<div class="form-row">
				<div class="form-group col-md-5">
					<label for="nim">NIM</label>
					<input id="nim" name="nim" class="form-control<?= invalid('nim') ?>" value="<?= nilai('nim', $row) ?>" maxlength="10" placeholder="10 digit">
					<?= pesan_error('nim') ?>
				</div>
				<div class="form-group col-md-7">
					<label for="nama">Nama lengkap</label>
					<input id="nama" name="nama" class="form-control<?= invalid('nama') ?>" value="<?= nilai('nama', $row) ?>">
					<?= pesan_error('nama') ?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label class="d-block">Jenis kelamin</label>
					<?php foreach (array('L' => 'Laki-laki', 'P' => 'Perempuan') as $v => $l): ?>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="g<?= $v ?>" name="gender" value="<?= $v ?>" class="custom-control-input" <?= set_radio('gender', $v, nilai('gender', $row, 'L') === $v) ?>>
							<label class="custom-control-label" for="g<?= $v ?>"><?= $l ?></label>
						</div>
					<?php endforeach; ?>
					<?= form_error('gender', '<div class="text-danger small">', '</div>') ?>
				</div>
				<div class="form-group col-md-5">
					<label for="prodi">Program studi</label>
					<input id="prodi" name="prodi" class="form-control<?= invalid('prodi') ?>" value="<?= nilai('prodi', $row, 'Teknik Informatika') ?>">
					<?= pesan_error('prodi') ?>
				</div>
				<div class="form-group col-md-3">
					<label for="angkatan">Angkatan</label>
					<input id="angkatan" name="angkatan" type="number" class="form-control<?= invalid('angkatan') ?>" value="<?= nilai('angkatan', $row, date('Y')) ?>">
					<?= pesan_error('angkatan') ?>
				</div>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input id="email" name="email" type="email" class="form-control<?= invalid('email') ?>" value="<?= nilai('email', $row) ?>">
				<?= pesan_error('email') ?>
			</div>
			<?php if ( ! $row): ?>
				<p class="text-muted small mb-0"><i class="fas fa-info-circle"></i> Akun login dibuat otomatis dengan username dan password awal = NIM.</p>
			<?php endif; ?>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/mahasiswa') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
