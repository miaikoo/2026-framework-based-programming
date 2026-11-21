<div class="card card-primary card-outline" style="max-width:820px">
	<?= form_open($row ? 'admin/kelas/edit/'.$row->id : 'admin/kelas/tambah') ?>
		<div class="card-body">
			<div class="form-row">
				<div class="form-group col-md-8">
					<label for="matakuliah_id">Mata kuliah</label>
					<?= form_dropdown('matakuliah_id', array('' => '- pilih -') + $opsi_mk, set_value('matakuliah_id', $row ? $row->matakuliah_id : ''), 'id="matakuliah_id" class="custom-select'.invalid('matakuliah_id').'"') ?>
					<?= pesan_error('matakuliah_id') ?>
				</div>
				<div class="form-group col-md-4">
					<label for="nama_kelas">Nama kelas</label>
					<input id="nama_kelas" name="nama_kelas" class="form-control text-uppercase<?= invalid('nama_kelas') ?>" value="<?= nilai('nama_kelas', $row, 'A') ?>" maxlength="2">
					<?= pesan_error('nama_kelas') ?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-8">
					<label for="dosen_id">Dosen pengampu</label>
					<?= form_dropdown('dosen_id', array('' => '- pilih -') + $opsi_dosen, set_value('dosen_id', $row ? $row->dosen_id : ''), 'id="dosen_id" class="custom-select'.invalid('dosen_id').'"') ?>
					<?= pesan_error('dosen_id') ?>
				</div>
				<div class="form-group col-md-4">
					<label for="tahun_ajaran">Tahun ajaran</label>
					<input id="tahun_ajaran" name="tahun_ajaran" class="form-control<?= invalid('tahun_ajaran') ?>" value="<?= nilai('tahun_ajaran', $row, '2026/2027 Ganjil') ?>">
					<?= pesan_error('tahun_ajaran') ?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-3">
					<label for="hari">Hari</label>
					<?= form_dropdown('hari', array_combine(Kelas_model::HARI, Kelas_model::HARI), set_value('hari', $row ? $row->hari : 'Senin'), 'id="hari" class="custom-select"') ?>
				</div>
				<div class="form-group col-md-3">
					<label for="jam_mulai">Jam mulai</label>
					<input id="jam_mulai" name="jam_mulai" type="time" class="form-control<?= invalid('jam_mulai') ?>" value="<?= set_value('jam_mulai', $row ? jam($row->jam_mulai) : '07:30') ?>">
					<?= pesan_error('jam_mulai') ?>
				</div>
				<div class="form-group col-md-3">
					<label for="jam_selesai">Jam selesai</label>
					<input id="jam_selesai" name="jam_selesai" type="time" class="form-control<?= invalid('jam_selesai') ?>" value="<?= set_value('jam_selesai', $row ? jam($row->jam_selesai) : '10:00') ?>">
					<?= pesan_error('jam_selesai') ?>
				</div>
				<div class="form-group col-md-3">
					<label for="ruang">Ruang</label>
					<input id="ruang" name="ruang" class="form-control<?= invalid('ruang') ?>" value="<?= nilai('ruang', $row) ?>">
					<?= pesan_error('ruang') ?>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/kelas') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
