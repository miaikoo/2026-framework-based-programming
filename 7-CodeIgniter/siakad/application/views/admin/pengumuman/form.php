<div class="card card-primary card-outline" style="max-width:760px">
	<?= form_open($row ? 'admin/pengumuman/edit/'.$row->id : 'admin/pengumuman/tambah') ?>
		<div class="card-body">
			<div class="form-group">
				<label for="judul">Judul</label>
				<input id="judul" name="judul" class="form-control<?= invalid('judul') ?>" value="<?= nilai('judul', $row) ?>">
				<?= pesan_error('judul') ?>
			</div>
			<div class="form-group">
				<label for="isi">Isi pengumuman</label>
				<textarea id="isi" name="isi" rows="5" class="form-control<?= invalid('isi') ?>"><?= nilai('isi', $row) ?></textarea>
				<?= pesan_error('isi') ?>
			</div>
			<div class="form-group">
				<label for="untuk">Ditujukan untuk</label>
				<?= form_dropdown('untuk', array('semua' => 'Semua pengguna', 'dosen' => 'Dosen saja', 'mahasiswa' => 'Mahasiswa saja'),
					set_value('untuk', $row ? $row->untuk : 'semua'), 'id="untuk" class="custom-select"') ?>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/pengumuman') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
