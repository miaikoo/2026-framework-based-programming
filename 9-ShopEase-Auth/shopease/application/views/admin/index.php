<?php $total = array_sum($per_peran); ?>
<div class="container page-head pb-5">
	<?php $this->load->view('partials/panel_nav'); ?>
	<h1 class="mt-4" style="font-size:clamp(1.8rem,3vw,2.4rem)">Dashboard admin</h1>
	<p class="text-steel">Ringkasan akun dan aktivitas login toko.</p>

	<div class="angka-grid">
		<div class="angka"><b><?= $total ?></b><span>pengguna terdaftar</span></div>
		<div class="angka"><b><?= $per_peran['customer'] ?></b><span>customer</span></div>
		<div class="angka"><b><?= $per_peran['operator'] + $per_peran['admin'] ?></b><span>staf (operator &amp; admin)</span></div>
		<div class="angka"><b><?= $baru_7hari ?></b><span>daftar 7 hari terakhir</span></div>
		<div class="angka<?= $login24->gagal ? ' angka-waspada' : '' ?>"><b><?= $login24->sukses ?> / <?= $login24->gagal ?></b><span>login berhasil / gagal (24 jam)</span></div>
	</div>

	<div class="row g-4 mt-1">
		<div class="col-lg-6">
			<section class="kotak h-100">
				<div class="d-flex justify-content-between align-items-baseline">
					<h2 class="h5">Pendaftar terbaru</h2>
					<a href="<?= site_url('admin/pengguna') ?>" class="small">Kelola pengguna</a>
				</div>
				<ul class="daftar-orang">
					<?php foreach ($terbaru as $t): ?>
						<li>
							<span class="inisial" aria-hidden="true"><?= html_escape(mb_strtoupper(mb_substr($t->name, 0, 1))) ?></span>
							<span class="flex-grow-1"><b><?= html_escape($t->name) ?></b><small class="d-block text-steel"><?= html_escape($t->email) ?></small></span>
							<span class="text-end"><?= badge_peran($t->role) ?><small class="d-block text-steel"><?= waktu_lalu($t->created_at) ?></small></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		</div>
		<div class="col-lg-6">
			<section class="kotak h-100">
				<h2 class="h5">Aktivitas login terakhir</h2>
				<table class="table table-sm align-middle mb-0 tabel-log">
					<thead><tr><th>Waktu</th><th>Email</th><th>Hasil</th></tr></thead>
					<tbody>
						<?php foreach ($aktivitas as $a): ?>
							<tr>
								<td class="text-nowrap small"><?= date('d/m H:i', strtotime($a->created_at)) ?></td>
								<td class="small"><?= html_escape($a->email) ?><?= $a->name ? '' : ' <span class="text-steel">(tidak terdaftar)</span>' ?></td>
								<td><?= $a->berhasil ? '<span class="hasil hasil-ok">Berhasil</span>' : '<span class="hasil hasil-gagal">Gagal</span>' ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if ( ! $aktivitas): ?><tr><td colspan="3" class="text-steel">Belum ada percobaan login.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</section>
		</div>
	</div>
</div>
