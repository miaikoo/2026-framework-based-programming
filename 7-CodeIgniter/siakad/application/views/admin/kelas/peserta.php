<div class="card card-success card-outline">
	<div class="card-header">
		<h3 class="card-title"><?= html_escape($kelas->matakuliah) ?> &middot; Kelas <?= $kelas->nama_kelas ?>
			<small class="text-muted ml-2"><?= $kelas->hari ?>, <?= jam($kelas->jam_mulai) ?> &middot; <?= html_escape($kelas->dosen) ?></small></h3>
	</div>
	<?= form_open('admin/kelas/peserta/'.$kelas->id) ?>
		<div class="card-body">
			<div class="mb-2">
				<button type="button" class="btn btn-xs btn-default" onclick="document.querySelectorAll('.cek-mhs').forEach(c => c.checked = true); hitung()">Pilih semua</button>
				<button type="button" class="btn btn-xs btn-default" onclick="document.querySelectorAll('.cek-mhs').forEach(c => c.checked = false); hitung()">Kosongkan</button>
				<span class="ml-2 text-muted"><b id="jumlahDipilih"><?= count($terpilih) ?></b> mahasiswa dipilih</span>
			</div>
			<div class="row">
				<?php foreach ($mahasiswa as $m): ?>
					<div class="col-md-6 col-lg-4">
						<div class="custom-control custom-checkbox py-1">
							<input type="checkbox" class="custom-control-input cek-mhs" id="m<?= $m->id ?>" name="mahasiswa_id[]" value="<?= $m->id ?>"
								<?= in_array((int) $m->id, $terpilih, TRUE) ? 'checked' : '' ?> onchange="hitung()">
							<label class="custom-control-label" for="m<?= $m->id ?>"><?= $m->nim ?> &middot; <?= html_escape($m->nama) ?></label>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan peserta</button>
			<a href="<?= site_url('admin/kelas') ?>" class="btn btn-default">Kembali</a>
		</div>
	<?= form_close() ?>
</div>
<script>
	function hitung() { document.getElementById('jumlahDipilih').textContent = document.querySelectorAll('.cek-mhs:checked').length; }
</script>
