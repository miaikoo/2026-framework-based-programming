<div class="row">
	<?php foreach ($rekap as $r): ?>
		<?php $boleh = Presensi_model::boleh_uas($r->persen); ?>
		<div class="col-md-6">
			<div class="card card-outline card-<?= warna_persen($r->persen) ?>">
				<div class="card-header">
					<h3 class="card-title"><code><?= $r->kode ?></code> <?= html_escape($r->matakuliah) ?></h3>
					<div class="card-tools">
						<?= $boleh ? '<span class="badge badge-success">Boleh UAS</span>' : '<span class="badge badge-danger">Tidak boleh UAS</span>' ?>
					</div>
				</div>
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="display-4 font-weight-bold text-<?= warna_persen($r->persen) ?> mr-3" style="font-size:2.6rem"><?= $r->persen === NULL ? '-' : round($r->persen).'%' ?></div>
						<div class="flex-grow-1">
							<div class="progress mb-2"><div class="progress-bar bg-<?= warna_persen($r->persen) ?>" style="width:<?= (float) $r->persen ?>%"></div></div>
							<small>
								<?= badge_presensi('H', TRUE) ?> <?= $r->hadir ?> &nbsp; <?= badge_presensi('I', TRUE) ?> <?= $r->izin ?> &nbsp;
								<?= badge_presensi('S', TRUE) ?> <?= $r->sakit ?> &nbsp; <?= badge_presensi('A', TRUE) ?> <?= $r->alpa ?>
								&nbsp;dari <?= $r->total_pertemuan ?> pertemuan
							</small>
						</div>
					</div>
				</div>
				<div class="card-footer small d-flex justify-content-between">
					<span class="text-muted"><?= html_escape($r->dosen) ?> &middot; <?= $r->hari ?> <?= jam($r->jam_mulai) ?></span>
					<a href="<?= site_url('mahasiswa/kehadiran/detail/'.$r->kelas_id) ?>">Riwayat <i class="fas fa-arrow-right"></i></a>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
