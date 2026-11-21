<?php if ($sesi_aktif): ?>
	<?php foreach ($sesi_aktif as $s): ?>
		<div class="alert <?= $s->status_saya ? 'alert-success' : 'alert-primary' ?> d-flex justify-content-between align-items-center">
			<div>
				<i class="fas fa-broadcast-tower mr-1"></i>
				Sesi presensi <b><?= html_escape($s->matakuliah) ?></b> pertemuan ke-<?= $s->pertemuan_ke ?> sedang dibuka
				(sisa waktu <b data-countdown="<?= $s->berakhir_at ?>"></b>).
			</div>
			<?php if ($s->status_saya): ?>
				<span><i class="fas fa-check-circle"></i> Anda sudah presensi</span>
			<?php else: ?>
				<a href="<?= site_url('mahasiswa/presensi') ?>" class="btn btn-light btn-sm text-primary font-weight-bold">Isi presensi</a>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<div class="row">
	<div class="col-lg-7">
		<div class="card card-primary card-outline">
			<div class="card-header"><h3 class="card-title"><i class="fas fa-clock mr-1"></i> Kuliah hari ini (<?= Kelas_model::nama_hari() ?>)</h3></div>
			<div class="card-body">
				<?php if ( ! $hari_ini): ?><p class="text-muted mb-0">Tidak ada kuliah hari ini.</p><?php endif; ?>
				<?php foreach ($hari_ini as $k): ?>
					<div class="jadwal-item">
						<b><?= html_escape($k->matakuliah) ?></b> (<?= $k->sks ?> SKS)<br>
						<small class="text-muted"><i class="far fa-clock"></i> <?= jam($k->jam_mulai) ?>–<?= jam($k->jam_selesai) ?> &middot;
							<i class="fas fa-door-open"></i> <?= html_escape($k->ruang) ?> &middot; <?= html_escape($k->dosen) ?></small>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Ringkasan kehadiran</h3>
				<div class="card-tools"><a href="<?= site_url('mahasiswa/kehadiran') ?>" class="btn btn-tool">Detail <i class="fas fa-arrow-right"></i></a></div>
			</div>
			<div class="card-body">
				<?php foreach ($rekap as $r): ?>
					<div class="mb-3">
						<div class="d-flex justify-content-between">
							<span><?= html_escape($r->matakuliah) ?></span>
							<span class="font-weight-bold text-<?= warna_persen($r->persen) ?>"><?= $r->persen === NULL ? '-' : $r->persen.'%' ?></span>
						</div>
						<div class="progress progress-sm"><div class="progress-bar bg-<?= warna_persen($r->persen) ?>" style="width:<?= (float) $r->persen ?>%"></div></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php $this->load->view('dashboard/_pengumuman'); ?>
	</div>
</div>
