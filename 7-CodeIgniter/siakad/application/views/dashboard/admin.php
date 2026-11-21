<div class="row">
	<?php foreach (array(
		array('mahasiswa',  'bg-info',    'fa-user-graduate',      'Mahasiswa',   'admin/mahasiswa'),
		array('dosen',      'bg-success', 'fa-chalkboard-teacher', 'Dosen',       'admin/dosen'),
		array('matakuliah', 'bg-warning', 'fa-book',               'Mata Kuliah', 'admin/matakuliah'),
		array('kelas',      'bg-danger',  'fa-calendar-alt',       'Kelas Aktif', 'admin/kelas'),
	) as $b): ?>
		<div class="col-lg-3 col-6">
			<div class="small-box <?= $b[1] ?>">
				<div class="inner"><h3><?= $jumlah[$b[0]] ?></h3><p><?= $b[3] ?></p></div>
				<div class="icon"><i class="fas <?= $b[2] ?>"></i></div>
				<a href="<?= site_url($b[4]) ?>" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<div class="row">
	<div class="col-lg-7">
		<div class="card card-outline card-danger">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-user-times mr-1"></i> Kehadiran di bawah <?= Presensi_model::BATAS_UAS ?>% (tidak boleh UAS)</h3>
			</div>
			<div class="card-body p-0">
				<table class="table table-sm table-striped mb-0">
					<thead><tr><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th class="text-center">Hadir</th><th style="width:140px">Persentase</th></tr></thead>
					<tbody>
						<?php foreach ($bawah_batas as $r): ?>
							<tr>
								<td><?= $r->nim ?></td>
								<td><?= html_escape($r->nama) ?></td>
								<td><?= html_escape($r->matakuliah) ?></td>
								<td class="text-center"><?= $r->hadir ?>/<?= $r->total_pertemuan ?></td>
								<td>
									<div class="progress progress-xs mb-1"><div class="progress-bar bg-danger" style="width:<?= $r->persen ?>%"></div></div>
									<small class="text-danger font-weight-bold"><?= $r->persen ?>%</small>
								</td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $bawah_batas): ?><tr><td colspan="5" class="text-center text-muted p-3">Semua mahasiswa memenuhi syarat UAS.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card card-outline card-primary">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-broadcast-tower mr-1"></i> Sesi presensi yang sedang dibuka</h3></div>
			<div class="card-body p-0">
				<table class="table table-sm mb-0">
					<thead><tr><th>Mata Kuliah</th><th>Dosen</th><th>Ruang</th><th>Pertemuan</th><th>Berakhir</th></tr></thead>
					<tbody>
						<?php foreach ($sesi_aktif as $s): ?>
							<tr>
								<td><?= html_escape($s->matakuliah) ?></td><td><?= html_escape($s->dosen) ?></td><td><?= html_escape($s->ruang) ?></td>
								<td>ke-<?= $s->pertemuan_ke ?></td><td><?= date('H:i', strtotime($s->berakhir_at)) ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $sesi_aktif): ?><tr><td colspan="5" class="text-center text-muted p-3">Tidak ada sesi yang sedang dibuka.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php $this->load->view('dashboard/_pengumuman'); ?>
	</div>
</div>
