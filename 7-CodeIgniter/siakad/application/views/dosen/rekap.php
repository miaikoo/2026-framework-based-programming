<p class="mt-n2">
	<a href="<?= site_url('dosen/mengajar/kelas/'.$kelas->id) ?>"><i class="fas fa-arrow-left"></i> <?= html_escape($kelas->matakuliah) ?></a>
	<span class="text-muted">&middot; Kelas <?= $kelas->nama_kelas ?> &middot; batas kehadiran UAS <?= Presensi_model::BATAS_UAS ?>%</span>
</p>

<div class="card">
	<div class="card-body p-0 table-responsive">
		<table class="table table-bordered table-sm matriks mb-0">
			<thead class="thead-light">
				<tr>
					<th class="nama">NIM</th><th class="nama">Nama</th>
					<?php foreach ($pertemuan as $p): ?>
						<th title="<?= html_escape($p->topik) ?> (<?= tanggal_indo($p->tanggal) ?>)"><?= $p->pertemuan_ke ?></th>
					<?php endforeach; ?>
					<th>H</th><th>I</th><th>S</th><th>A</th><th style="width:120px">%</th><th>UAS</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rekap as $r): ?>
					<?php $boleh = Presensi_model::boleh_uas($r->persen); ?>
					<tr class="<?= $boleh ? '' : 'table-danger' ?>">
						<td class="nama"><?= $r->nim ?></td>
						<td class="nama"><?= html_escape($r->nama) ?></td>
						<?php foreach ($pertemuan as $p): ?>
							<td><?= badge_presensi(isset($matriks[$r->mahasiswa_id][$p->id]) ? $matriks[$r->mahasiswa_id][$p->id] : NULL, TRUE) ?></td>
						<?php endforeach; ?>
						<td><?= $r->hadir ?></td><td><?= $r->izin ?></td><td><?= $r->sakit ?></td><td><?= $r->alpa ?></td>
						<td>
							<div class="progress progress-xs mb-1"><div class="progress-bar bg-<?= warna_persen($r->persen) ?>" style="width:<?= (float) $r->persen ?>%"></div></div>
							<b class="text-<?= warna_persen($r->persen) ?>"><?= $r->persen === NULL ? '-' : $r->persen.'%' ?></b>
						</td>
						<td><?= $boleh ? '<span class="badge badge-success">Boleh</span>' : '<span class="badge badge-danger">Tidak</span>' ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="card-footer small text-muted">
		Keterangan: <?= badge_presensi('H', TRUE) ?> Hadir &nbsp; <?= badge_presensi('I', TRUE) ?> Izin &nbsp; <?= badge_presensi('S', TRUE) ?> Sakit &nbsp;
		<?= badge_presensi('A', TRUE) ?> Alpa &nbsp; <?= badge_presensi(NULL, TRUE) ?> belum tercatat (sesi masih dibuka / belum terdaftar). Persentase = hadir ÷ pertemuan yang tercatat untuk mahasiswa tsb.
	</div>
</div>
