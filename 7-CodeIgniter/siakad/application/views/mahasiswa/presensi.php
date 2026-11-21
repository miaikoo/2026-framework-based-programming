<div class="row">
	<div class="col-lg-5">
		<div class="card card-primary">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-key mr-1"></i> Masukkan kode dari dosen</h3></div>
			<?= form_open('mahasiswa/presensi/kirim') ?>
				<div class="card-body">
					<input name="kode" class="form-control form-control-lg input-kode" maxlength="6" placeholder="------" autocomplete="off" autofocus required>
					<small class="form-text text-muted text-center">6 karakter, huruf besar/kecil tidak berpengaruh.</small>
				</div>
				<div class="card-footer"><button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fas fa-check mr-1"></i> Kirim presensi</button></div>
			<?= form_close() ?>
		</div>
	</div>
	<div class="col-lg-7">
		<div class="card">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-broadcast-tower mr-1"></i> Sesi presensi yang sedang dibuka untuk Anda</h3></div>
			<div class="card-body p-0">
				<table class="table mb-0">
					<thead><tr><th>Mata kuliah</th><th>Pertemuan</th><th>Sisa waktu</th><th>Status Anda</th></tr></thead>
					<tbody>
						<?php foreach ($sesi_aktif as $s): ?>
							<tr>
								<td><?= html_escape($s->matakuliah) ?><br><small class="text-muted"><?= html_escape($s->dosen) ?></small></td>
								<td>ke-<?= $s->pertemuan_ke ?><br><small class="text-muted"><?= html_escape($s->topik) ?></small></td>
								<td><b data-countdown="<?= $s->berakhir_at ?>"></b></td>
								<td><?= badge_presensi($s->status_saya) ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $sesi_aktif): ?><tr><td colspan="4" class="text-center text-muted p-4">Belum ada sesi yang dibuka. Tunggu dosen membuka presensi di kelas.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="callout callout-warning small">
			Kode hanya berlaku selama sesi dibuka. Setelah sesi ditutup, mahasiswa yang belum presensi otomatis tercatat <b>Alpa</b>.
			Kehadiran minimal <b><?= Presensi_model::BATAS_UAS ?>%</b> untuk bisa mengikuti UAS.
		</div>
	</div>
</div>
