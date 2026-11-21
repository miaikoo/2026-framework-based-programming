<p class="text-muted mt-n2">Tahun ajaran 2026/2027 Ganjil &middot; total <b><?= $total_sks ?> SKS</b></p>
<div class="row">
	<?php foreach ($per_hari as $hari => $items): ?>
		<div class="col">
			<div class="card jadwal-hari <?= $hari === $hari_ini ? 'hari-ini' : '' ?>">
				<div class="card-header py-2">
					<b><?= $hari ?></b>
					<?php if ($hari === $hari_ini): ?><span class="badge badge-primary float-right">hari ini</span><?php endif; ?>
				</div>
				<div class="card-body p-2">
					<?php foreach ($items as $k): ?>
						<div class="jadwal-item">
							<div class="small text-muted"><?= jam($k->jam_mulai) ?>–<?= jam($k->jam_selesai) ?></div>
							<b><?= html_escape($k->matakuliah) ?></b>
							<div class="small"><?= html_escape($k->ruang) ?> &middot; <?= $k->sks ?> SKS</div>
							<div class="small text-muted text-truncate"><?= html_escape($k->dosen) ?></div>
						</div>
					<?php endforeach; ?>
					<?php if ( ! $items): ?><p class="text-muted small text-center mt-4">Tidak ada kuliah</p><?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
