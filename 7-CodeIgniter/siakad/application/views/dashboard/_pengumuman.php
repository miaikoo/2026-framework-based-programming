<div class="card card-outline card-warning">
	<div class="card-header"><h3 class="card-title"><i class="fas fa-bullhorn mr-1"></i> Pengumuman</h3></div>
	<div class="card-body p-0">
		<?php if ( ! $pengumuman): ?>
			<p class="text-muted p-3 mb-0">Belum ada pengumuman.</p>
		<?php endif; ?>
		<?php foreach ($pengumuman as $p): ?>
			<div class="pengumuman-item p-3">
				<div class="d-flex justify-content-between">
					<b><?= html_escape($p->judul) ?></b>
					<small class="text-muted text-nowrap ml-2"><?= tanggal_indo($p->created_at) ?></small>
				</div>
				<div class="small mt-1"><?= nl2br(html_escape($p->isi)) ?></div>
				<?php if ($me->role === 'admin'): ?><span class="badge badge-secondary mt-1">untuk: <?= $p->untuk ?></span><?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
