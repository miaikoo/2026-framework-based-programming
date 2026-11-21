<?php
$seg = $this->uri->segment(2) ?: 'dashboard';
$menu = array(
	array('dashboard', 'fa-tachometer-alt', 'Dashboard'),
	array('pesanan',   'fa-receipt',        'Pesanan'),
	array('rekap',     'fa-clipboard-list', 'Rekap Produksi'),
	array('header',    'KATALOG'),
	array('batch',     'fa-layer-group',    'Batch PO'),
	array('produk',    'fa-tshirt',         'Produk'),
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?> | Admin MerchHima</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=swap">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/merch.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
			<li class="nav-item d-none d-sm-inline-block"><a href="<?= base_url() ?>" class="nav-link" target="_blank"><i class="fas fa-store mr-1"></i>Lihat toko</a></li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item"><span class="nav-link"><i class="far fa-user-circle mr-1"></i><?= html_escape($me->nama) ?></span></li>
			<li class="nav-item"><a class="nav-link text-danger" href="<?= site_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
		</ul>
	</nav>

	<aside class="main-sidebar sidebar-dark-warning elevation-4">
		<a href="<?= site_url('admin') ?>" class="brand-link">
			<span class="brand-badge ml-2 mr-2"><i class="fas fa-tshirt"></i></span>
			<span class="brand-text font-weight-bold">MerchHima Admin</span>
		</a>
		<div class="sidebar">
			<nav class="mt-3">
				<ul class="nav nav-pills nav-sidebar flex-column" role="menu">
					<?php foreach ($menu as $m): ?>
						<?php if ($m[0] === 'header'): ?>
							<li class="nav-header"><?= $m[1] ?></li>
						<?php else: ?>
							<li class="nav-item">
								<a href="<?= site_url('admin/'.$m[0]) ?>" class="nav-link<?= $seg === $m[0] ? ' active' : '' ?>">
									<i class="nav-icon fas <?= $m[1] ?>"></i><p><?= $m[2] ?></p>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
	</aside>

	<div class="content-wrapper">
		<div class="content-header"><div class="container-fluid"><h1 class="m-0"><?= html_escape($title) ?></h1></div></div>
		<section class="content">
			<div class="container-fluid">
				<?php if ($flash = $this->session->flashdata('flash')): ?>
					<div class="alert alert-<?= $flash['type'] ?> alert-dismissible">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?= $flash['message'] ?>
					</div>
				<?php endif; ?>
				<?= $content ?>
			</div>
		</section>
	</div>
	<footer class="main-footer small"><b>MerchHima</b> &middot; Latihan 2 PBKK &middot; dimuat {elapsed_time} detik</footer>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<?= form_open('', array('class' => 'modal-content', 'id' => 'formHapus')) ?>
			<div class="modal-header bg-danger"><h5 class="modal-title">Konfirmasi hapus</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div>
			<div class="modal-body">Hapus <b id="hapusNama"></b>?</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-danger">Hapus</button>
			</div>
		<?= form_close() ?>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script>
	$('#modalHapus').on('show.bs.modal', function (e) {
		var b = $(e.relatedTarget);
		$('#formHapus').attr('action', b.data('hapus-url'));
		$('#hapusNama').text(b.data('hapus-nama'));
	});
</script>
<?php if (isset($scripts)) echo $scripts; ?>
</body>
</html>
