<div class="row">
	<div class="col-lg-7">
		<div class="card card-primary card-outline">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-clock mr-1"></i> Jadwal mengajar hari ini (<?= Kelas_model::nama_hari() ?>)</h3></div>
			<div class="card-body">
				<?php if ( ! $hari_ini): ?>
					<p class="text-muted mb-0">Tidak ada jadwal mengajar hari ini.</p>
				<?php endif; ?>
				<?php foreach ($hari_ini as $k): ?>
					<div class="callout callout-info mb-2 d-flex justify-content-between align-items-center">
						<div>
							<h5 class="mb-1"><?= html_escape($k->matakuliah) ?> <small class="text-muted">Kelas <?= $k->nama_kelas ?></small></h5>
							<span class="text-muted"><i class="far fa-clock"></i> <?= jam($k->jam_mulai) ?>–<?= jam($k->jam_selesai) ?> &middot;
								<i class="fas fa-door-open"></i> <?= html_escape($k->ruang) ?> &middot; <?= $k->jumlah_peserta ?> mahasiswa</span>
						</div>
						<?php if ($k->sesi_aktif): ?>
							<a class="btn btn-success" href="<?= site_url('dosen/mengajar/sesi/'.$k->sesi_aktif->id) ?>"><i class="fas fa-broadcast-tower"></i> Sesi berjalan</a>
						<?php else: ?>
							<a class="btn btn-primary" href="<?= site_url('dosen/mengajar/kelas/'.$k->id) ?>"><i class="fas fa-play"></i> Buka presensi</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Semua kelas saya</h3></div>
			<div class="card-body p-0">
				<table class="table table-hover mb-0">
					<thead><tr><th>Mata Kuliah</th><th>Jadwal</th><th>Ruang</th><th class="text-center">Peserta</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($kelas as $k): ?>
							<tr>
								<td><?= $k->kode ?> &middot; <?= html_escape($k->matakuliah) ?> <?= $k->nama_kelas ?></td>
								<td><?= $k->hari ?>, <?= jam($k->jam_mulai) ?></td>
								<td><?= html_escape($k->ruang) ?></td>
								<td class="text-center"><?= $k->jumlah_peserta ?></td>
								<td class="text-right"><a href="<?= site_url('dosen/mengajar/kelas/'.$k->id) ?>" class="btn btn-xs btn-outline-primary">Buka</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php $this->load->view('dashboard/_pengumuman'); ?>
	</div>
</div>
