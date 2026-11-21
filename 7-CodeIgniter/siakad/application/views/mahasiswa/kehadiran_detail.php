<p class="mt-n2">
	<a href="<?= site_url('mahasiswa/kehadiran') ?>"><i class="fas fa-arrow-left"></i> Rekap kehadiran</a>
	<span class="text-muted">&middot; <?= html_escape($kelas->matakuliah) ?> &middot; <?= html_escape($kelas->dosen) ?></span>
</p>
<div class="card">
	<div class="card-body p-0">
		<table class="table table-striped mb-0">
			<thead><tr><th class="text-center">Ke</th><th>Tanggal</th><th>Topik</th><th>Status</th><th>Waktu presensi</th><th>Keterangan</th></tr></thead>
			<tbody>
				<?php foreach ($riwayat as $r): ?>
					<tr>
						<td class="text-center"><?= $r->pertemuan_ke ?></td>
						<td class="text-nowrap"><?= tanggal_indo($r->tanggal, TRUE) ?></td>
						<td><?= html_escape($r->topik) ?></td>
						<td><?= $r->status ? badge_presensi($r->status) : ($r->status_sesi === 'dibuka' ? '<span class="badge badge-primary">sesi dibuka</span>' : badge_presensi(NULL)) ?></td>
						<td><?= $r->waktu ? date('H:i', strtotime($r->waktu)) : '-' ?></td>
						<td class="small text-muted"><?= html_escape((string) $r->keterangan) ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
