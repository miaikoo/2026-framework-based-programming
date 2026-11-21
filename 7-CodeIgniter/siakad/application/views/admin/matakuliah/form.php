<div class="card card-primary card-outline" style="max-width:640px">
	<?= form_open($row ? 'admin/matakuliah/edit/'.$row->id : 'admin/matakuliah/tambah') ?>
		<div class="card-body">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="kode">Kode</label>
					<input id="kode" name="kode" class="form-control text-uppercase<?= invalid('kode') ?>" value="<?= nilai('kode', $row) ?>" maxlength="6" placeholder="IF2305">
					<?= pesan_error('kode') ?>
				</div>
				<div class="form-group col-md-8">
					<label for="nama">Nama mata kuliah</label>
					<input id="nama" name="nama" class="form-control<?= invalid('nama') ?>" value="<?= nilai('nama', $row) ?>">
					<?= pesan_error('nama') ?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="sks">SKS</label>
					<select id="sks" name="sks" class="custom-select<?= invalid('sks') ?>">
						<?php for ($i = 1; $i <= 6; $i++): ?><option <?= set_select('sks', $i, (int) nilai('sks', $row, 3) === $i) ?>><?= $i ?></option><?php endfor; ?>
					</select>
					<?= pesan_error('sks') ?>
				</div>
				<div class="form-group col-md-6">
					<label for="semester">Semester</label>
					<select id="semester" name="semester" class="custom-select<?= invalid('semester') ?>">
						<?php for ($i = 1; $i <= 8; $i++): ?><option <?= set_select('semester', $i, (int) nilai('semester', $row, 1) === $i) ?>><?= $i ?></option><?php endfor; ?>
					</select>
					<?= pesan_error('semester') ?>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/matakuliah') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
