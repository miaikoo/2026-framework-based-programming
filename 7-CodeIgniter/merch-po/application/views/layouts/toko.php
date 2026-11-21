<?php $jumlah_keranjang = $this->keranjang->jumlah(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?> | MerchHima</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=swap">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/merch.css') ?>">
</head>
<!-- AdminLTE varian "top navigation": tanpa sidebar, cocok untuk halaman toko -->
<body class="hold-transition layout-top-nav">
<div class="wrapper">
	<nav class="main-header navbar navbar-expand-md navbar-dark navbar-merch">
		<div class="container">
			<a href="<?= base_url() ?>" class="navbar-brand">
				<span class="brand-badge"><i class="fas fa-tshirt"></i></span>
				<span class="brand-text font-weight-bold">MerchHima</span>
			</a>
			<ul class="navbar-nav">
				<li class="nav-item"><a href="<?= base_url() ?>" class="nav-link">Katalog</a></li>
				<?php if ($me && $me->role === 'pembeli'): ?>
					<li class="nav-item"><a href="<?= site_url('pesanan') ?>" class="nav-link">Pesanan Saya</a></li>
				<?php endif; ?>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?= site_url('keranjang') ?>" aria-label="Keranjang">
						<i class="fas fa-shopping-bag"></i>
						<?php if ($jumlah_keranjang): ?><span class="badge badge-warning navbar-badge"><?= $jumlah_keranjang ?></span><?php endif; ?>
					</a>
				</li>
				<?php if ($me): ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><i class="far fa-user-circle mr-1"></i><?= html_escape($me->nama) ?></a>
						<div class="dropdown-menu dropdown-menu-right">
							<?php if ($me->role === 'admin'): ?>
								<a href="<?= site_url('admin') ?>" class="dropdown-item"><i class="fas fa-cogs mr-2"></i>Panel admin</a>
							<?php else: ?>
								<a href="<?= site_url('pesanan') ?>" class="dropdown-item"><i class="fas fa-receipt mr-2"></i>Pesanan saya</a>
							<?php endif; ?>
							<div class="dropdown-divider"></div>
							<a href="<?= site_url('logout') ?>" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt mr-2"></i>Keluar</a>
						</div>
					</li>
				<?php else: ?>
					<li class="nav-item"><a href="<?= site_url('login') ?>" class="nav-link">Masuk</a></li>
					<li class="nav-item"><a href="<?= site_url('daftar') ?>" class="btn btn-sm btn-warning ml-2 mt-1">Daftar</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>

	<div class="content-wrapper">
		<div class="content pt-3">
			<div class="container">
				<?php if ($flash = $this->session->flashdata('flash')): ?>
					<div class="alert alert-<?= $flash['type'] ?> alert-dismissible">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?= $flash['message'] ?>
					</div>
				<?php endif; ?>
				<?= $content ?>
			</div>
		</div>
	</div>

	<footer class="main-footer small">
		<div class="container d-flex justify-content-between">
			<span><b>MerchHima</b> &middot; preorder merchandise resmi himpunan (proyek latihan PBKK)</span>
			<span class="text-muted">CodeIgniter <?= CI_VERSION ?></span>
		</div>
	</footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script>
	// hitung mundur penutupan PO: elemen [data-tutup="YYYY-mm-dd HH:ii:ss"]
	document.querySelectorAll('[data-tutup]').forEach(function (el) {
		var end = new Date(el.dataset.tutup.replace(' ', 'T')).getTime();
		(function tick() {
			var s = Math.max(0, Math.floor((end - Date.now()) / 1000));
			var d = Math.floor(s / 86400), h = Math.floor(s % 86400 / 3600), m = Math.floor(s % 3600 / 60);
			el.textContent = s ? d + ' hari ' + h + ' jam ' + m + ' menit' : 'PO ditutup';
			if (s) setTimeout(tick, 30000);
		})();
	});
</script>
</body>
</html>
