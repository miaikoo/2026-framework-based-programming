<?php
// Timeline status pesanan: dipakai halaman pembeli & admin
$alur = array(
	'menunggu_pembayaran' => array('fa-shopping-cart', 'Dipesan'),
	'menunggu_verifikasi' => array('fa-wallet', 'Dibayar'),
	'lunas'               => array('fa-industry', 'Diproduksi'),
	'siap_diambil'        => array('fa-box-open', 'Siap diambil'),
	'selesai'             => array('fa-flag-checkered', 'Selesai'),
);
$urutan = array_keys($alur);
$posisi = array_search($o->status === 'ditolak' ? 'menunggu_pembayaran' : $o->status, $urutan, TRUE);
?>
<?php if ($o->status === 'dibatalkan'): ?>
	<div class="alert alert-dark mb-0"><i class="fas fa-ban mr-1"></i> Pesanan ini dibatalkan.</div>
<?php else: ?>
	<div class="timeline-status">
		<?php foreach ($urutan as $i => $st): ?>
			<?php $kelas = $i < $posisi || $o->status === 'selesai' ? 'done' : ($i === $posisi ? 'now' : ''); ?>
			<div class="step <?= $kelas ?>">
				<div class="dot"><i class="fas <?= $kelas === 'done' ? 'fa-check' : $alur[$st][0] ?>"></i></div>
				<?= $alur[$st][1] ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
