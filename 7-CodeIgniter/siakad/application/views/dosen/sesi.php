<?php $dibuka = $sesi->status === 'dibuka'; ?>
<p class="mt-n2">
	<a href="<?= site_url('dosen/mengajar/kelas/'.$kelas->id) ?>"><i class="fas fa-arrow-left"></i> <?= html_escape($kelas->matakuliah) ?></a>
	<span class="text-muted">&middot; <?= tanggal_indo($sesi->tanggal, TRUE) ?> &middot; <?= html_escape($sesi->topik) ?></span>
</p>

<div class="row">
	<div class="col-lg-4">
		<div class="card <?= $dibuka ? 'card-success' : 'card-secondary' ?>">
			<div class="card-header"><h3 class="card-title"><?= $dibuka ? 'Kode presensi aktif' : 'Sesi sudah ditutup' ?></h3></div>
			<div class="card-body text-center">
				<div class="kode-presensi mb-2" style="<?= $dibuka ? '' : 'opacity:.4;text-decoration:line-through' ?>"><?= $sesi->kode ?></div>
				<?php if ($dibuka): ?>
					<p class="mb-0">Sisa waktu <b class="h5" data-countdown="<?= $sesi->berakhir_at ?>"></b></p>
					<small class="text-muted">berakhir pukul <?= date('H:i', strtotime($sesi->berakhir_at)) ?></small>
				<?php else: ?>
					<small class="text-muted">dibuka <?= date('H:i', strtotime($sesi->dibuka_at)) ?> – ditutup <?= date('H:i', strtotime($sesi->berakhir_at)) ?></small>
				<?php endif; ?>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-around text-center mb-2">
					<div><div class="h3 mb-0 text-success"><?= $hadir ?></div><small>hadir</small></div>
					<div><div class="h3 mb-0"><?= count($daftar) ?></div><small>peserta</small></div>
				</div>
				<?php if ($dibuka): ?>
					<?= form_open('dosen/mengajar/tutup/'.$sesi->id, array('onsubmit' => "return confirm('Tutup sesi sekarang? Mahasiswa yang belum presensi akan tercatat Alpa.')")) ?>
						<button type="submit" class="btn btn-danger btn-block"><i class="fas fa-stop-circle mr-1"></i> Tutup sesi</button>
					<?= form_close() ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="col-lg-8">
		<div class="card">
			<?= form_open('dosen/mengajar/status/'.$sesi->id) ?>
				<div class="card-header">
					<h3 class="card-title">Daftar hadir</h3>
					<div class="card-tools"><a href="<?= current_url() ?>" class="btn btn-tool"><i class="fas fa-sync-alt"></i> Muat ulang</a></div>
				</div>
				<div class="card-body p-0">
					<table class="table table-sm table-hover mb-0">
						<thead><tr><th>NIM</th><th>Nama</th><th>Waktu</th><th>Status</th><th style="width:130px">Ubah</th><th>Keterangan</th></tr></thead>
						<tbody>
							<?php foreach ($daftar as $d): ?>
								<tr>
									<td><?= $d->nim ?></td>
									<td><?= html_escape($d->nama) ?></td>
									<td><?= $d->waktu ? date('H:i:s', strtotime($d->waktu)) : '-' ?></td>
									<td><?= badge_presensi($d->status) ?></td>
									<td>
										<select name="status[<?= $d->mahasiswa_id ?>]" class="custom-select custom-select-sm">
											<option value="">–</option>
											<?php foreach (Presensi_model::STATUS as $k => $label): ?>
												<option value="<?= $k ?>" <?= $d->status === $k ? 'selected' : '' ?>><?= $label ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td><input name="keterangan[<?= $d->mahasiswa_id ?>]" class="form-control form-control-sm" value="<?= html_escape((string) $d->keterangan) ?>" maxlength="150"></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="card-footer d-flex justify-content-between align-items-center">
					<small class="text-muted">Ubah status untuk mahasiswa yang izin/sakit, lalu simpan.</small>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Simpan status</button>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>
