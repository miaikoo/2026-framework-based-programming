<?php
// datetime-local butuh format "Y-m-d\TH:i"
$dt = function ($field, $default) use ($row) {
	$v = set_value($field, $row ? $row->$field : $default);
	return $v ? date('Y-m-d\TH:i', strtotime($v)) : '';
};
?>
<div class="card card-warning card-outline" style="max-width:820px">
	<?= form_open($row ? 'admin/batch/edit/'.$row->id : 'admin/batch/tambah') ?>
		<div class="card-body">
			<div class="form-group">
				<label for="nama">Nama batch</label>
				<input id="nama" name="nama" class="form-control<?= invalid('nama') ?>" value="<?= nilai('nama', $row) ?>" placeholder="mis. PO Merch Makrab 2027">
				<?= pesan_error('nama') ?>
			</div>
			<div class="form-group">
				<label for="deskripsi">Deskripsi</label>
				<textarea id="deskripsi" name="deskripsi" rows="2" class="form-control"><?= nilai('deskripsi', $row) ?></textarea>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="dibuka_at">PO dibuka</label>
					<input id="dibuka_at" name="dibuka_at" type="datetime-local" class="form-control<?= invalid('dibuka_at') ?>" value="<?= $dt('dibuka_at', '') ?>">
					<?= pesan_error('dibuka_at') ?>
				</div>
				<div class="form-group col-md-4">
					<label for="ditutup_at">PO ditutup</label>
					<input id="ditutup_at" name="ditutup_at" type="datetime-local" class="form-control<?= invalid('ditutup_at') ?>" value="<?= $dt('ditutup_at', '') ?>">
					<?= pesan_error('ditutup_at') ?>
				</div>
				<div class="form-group col-md-4">
					<label for="estimasi_ambil">Estimasi barang diambil</label>
					<input id="estimasi_ambil" name="estimasi_ambil" type="date" class="form-control<?= invalid('estimasi_ambil') ?>" value="<?= nilai('estimasi_ambil', $row) ?>">
					<?= pesan_error('estimasi_ambil') ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rekening">Rekening tujuan transfer</label>
				<input id="rekening" name="rekening" class="form-control<?= invalid('rekening') ?>" value="<?= nilai('rekening', $row, 'BNI 0123456789 a.n. Bendahara Himpunan (contoh)') ?>">
				<?= pesan_error('rekening') ?>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan</button>
			<a href="<?= site_url('admin/batch') ?>" class="btn btn-default">Batal</a>
		</div>
	<?= form_close() ?>
</div>
