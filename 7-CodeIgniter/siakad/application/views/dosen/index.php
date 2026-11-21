<div class="row">
	<?php foreach ($kelas as $k): ?>
		<div class="col-md-6 col-xl-4">
			<div class="card card-outline <?= $k->sesi_aktif ? 'card-success' : 'card-primary' ?>">
				<div class="card-header">
					<h3 class="card-title"><?= html_escape($k->matakuliah) ?></h3>
					<div class="card-tools"><span class="badge badge-secondary"><?= $k->kode ?> &middot; <?= $k->nama_kelas ?></span></div>
				</div>
				<div class="card-body">
					<p class="mb-1"><i class="far fa-calendar-alt fa-fw text-muted"></i> <?= $k->hari ?>, <?= jam($k->jam_mulai) ?>–<?= jam($k->jam_selesai) ?></p>
					<p class="mb-1"><i class="fas fa-door-open fa-fw text-muted"></i> <?= html_escape($k->ruang) ?></p>
					<p class="mb-0"><i class="fas fa-users fa-fw text-muted"></i> <?= $k->jumlah_peserta ?> mahasiswa &middot; <?= $k->sks ?> SKS</p>
					<?php if ($k->sesi_aktif): ?>
						<div class="alert alert-success py-1 px-2 mt-2 mb-0 small"><i class="fas fa-broadcast-tower"></i> Sesi ke-<?= $k->sesi_aktif->pertemuan_ke ?> sedang dibuka</div>
					<?php endif; ?>
				</div>
				<div class="card-footer d-flex justify-content-between">
					<a href="<?= site_url('dosen/mengajar/kelas/'.$k->id) ?>" class="btn btn-sm btn-primary"><i class="fas fa-door-open"></i> Masuk kelas</a>
					<a href="<?= site_url('dosen/mengajar/rekap/'.$k->id) ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-table"></i> Rekap</a>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
