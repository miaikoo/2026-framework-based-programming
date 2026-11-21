<?php
// Ditampilkan oleh hook Penjaga_akses dengan status 403. Variabel: $izin, $halaman, $pengguna
$nama = array('customer' => 'customer', 'operator' => 'operator', 'admin' => 'admin');
$untuk = implode(' dan ', array_map(function ($r) use ($nama) { return $nama[$r]; }, $izin));
?>
<div class="container page-head pb-5">
	<div class="row g-5 align-items-center">
		<div class="col-lg-7">
			<p class="display-font fw-bold" style="font-size:5rem;line-height:1;color:var(--rail)">403</p>
			<h1 style="font-size:clamp(2rem,3.6vw,2.6rem)">Halaman ini khusus <?= $untuk ?></h1>
			<p class="text-steel measure">Kamu masuk sebagai <b><?= html_escape($pengguna->name) ?></b> dengan peran <?= badge_peran($pengguna->role) ?>, jadi <code>/<?= html_escape($halaman) ?></code> tidak bisa dibuka. Kalau kamu staf toko, minta admin mengubah peran akunmu.</p>
			<a class="btn btn-se mt-2" href="<?= site_url($this->auth->beranda_peran()) ?>">Ke halaman saya</a>
			<a class="btn btn-se btn-se-line mt-2" href="<?= base_url() ?>">Ke beranda toko</a>
		</div>
		<div class="col-lg-5">
			<div class="kotak">
				<h2 class="h6 mb-3">Siapa boleh membuka apa</h2>
				<table class="table table-sm mb-0">
					<thead><tr><th>Halaman</th><th>Peran</th></tr></thead>
					<tbody>
						<tr><td>/akun</td><td>semua yang sudah masuk</td></tr>
						<tr><td>/operator</td><td>operator, admin</td></tr>
						<tr><td>/admin</td><td>admin</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
