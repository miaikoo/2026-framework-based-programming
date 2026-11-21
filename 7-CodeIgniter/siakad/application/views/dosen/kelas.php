<p class="text-muted mt-n2">
	<?= $kelas->kode ?> &middot; Kelas <?= $kelas->nama_kelas ?> &middot; <?= $kelas->hari ?>, <?= jam($kelas->jam_mulai) ?>–<?= jam($kelas->jam_selesai) ?>
	&middot; <?= html_escape($kelas->ruang) ?> &middot; <?= $kelas->jumlah_peserta ?> mahasiswa
</p>

<div class="row">
	<div class="col-lg-4">
		<?php if ($sesi_aktif): ?>
			<div class="card card-success">
				<div class="card-header"><h3 class="card-title">Sesi sedang berjalan</h3></div>
				<div class="card-body text-center">
					<p class="mb-1">Pertemuan ke-<?= $sesi_aktif->pertemuan_ke ?></p>
					<div class="kode-presensi mb-2"><?= $sesi_aktif->kode ?></div>
					<p class="text-muted mb-0">Sisa waktu <b data-countdown="<?= $sesi_aktif->berakhir_at ?>"></b></p>
				</div>
				<div class="card-footer"><a href="<?= site_url('dosen/mengajar/sesi/'.$sesi_aktif->id) ?>" class="btn btn-success btn-block">Lihat daftar hadir</a></div>
			</div>
		<?php else: ?>
			<div class="card card-primary">
				<div class="card-header"><h3 class="card-title"><i class="fas fa-play mr-1"></i> Buka sesi pertemuan ke-<?= $berikutnya ?></h3></div>
				<?= form_open('dosen/mengajar/buka/'.$kelas->id) ?>
					<div class="card-body">
						<div class="form-group">
							<label for="topik">Topik / materi</label>
							<input id="topik" name="topik" class="form-control" placeholder="mis. Aksesibilitas antarmuka" required maxlength="150">
						</div>
						<div class="form-group mb-0">
							<label for="durasi">Kode berlaku selama</label>
							<select id="durasi" name="durasi" class="custom-select">
								<?php foreach (array(10, 15, 30, 60, 90) as $d): ?><option value="<?= $d ?>" <?= $d === 15 ? 'selected' : '' ?>><?= $d ?> menit</option><?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="card-footer"><button type="submit" class="btn btn-primary btn-block"><i class="fas fa-key mr-1"></i> Buat kode presensi</button></div>
				<?= form_close() ?>
			</div>
		<?php endif; ?>
		<a href="<?= site_url('dosen/mengajar/rekap/'.$kelas->id) ?>" class="btn btn-outline-secondary btn-block mb-3"><i class="fas fa-table mr-1"></i> Rekap kehadiran & kelayakan UAS</a>
	</div>

	<div class="col-lg-8">
		<div class="card">
			<div class="card-header"><h3 class="card-title">Riwayat pertemuan</h3></div>
			<div class="card-body p-0">
				<table class="table table-hover mb-0">
					<thead><tr><th class="text-center">Ke</th><th>Tanggal</th><th>Topik</th><th class="text-center">Hadir</th><th>Status</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($pertemuan as $p): ?>
							<tr>
								<td class="text-center"><?= $p->pertemuan_ke ?></td>
								<td class="text-nowrap"><?= tanggal_indo($p->tanggal) ?></td>
								<td><?= html_escape($p->topik) ?></td>
								<td class="text-center"><?= (int) $p->hadir ?>/<?= $kelas->jumlah_peserta ?></td>
								<td><?= $p->status === 'dibuka' ? '<span class="badge badge-success">dibuka</span>' : '<span class="badge badge-secondary">ditutup</span>' ?></td>
								<td class="text-right"><a href="<?= site_url('dosen/mengajar/sesi/'.$p->id) ?>" class="btn btn-xs btn-outline-primary">Detail</a></td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $pertemuan): ?><tr><td colspan="6" class="text-center text-muted p-3">Belum ada pertemuan.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
