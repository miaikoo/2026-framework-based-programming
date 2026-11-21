<?php
/**
 * Layout utama AdminLTE 3. Variabel: $title, $content (HTML hasil view halaman), $me (akun login).
 */
$seg = $this->uri->segment(1).'/'.$this->uri->segment(2);
$aktif = function ($prefix) use ($seg) {
	return strpos($seg.'/', $prefix.'/') === 0 || $seg === $prefix ? ' active' : '';
};

$menu = array(
	'admin' => array(
		array('header', 'MASTER DATA'),
		array('admin/mahasiswa',  'fa-user-graduate',    'Mahasiswa'),
		array('admin/dosen',      'fa-chalkboard-teacher', 'Dosen'),
		array('admin/matakuliah', 'fa-book',             'Mata Kuliah'),
		array('admin/kelas',      'fa-calendar-alt',     'Kelas & Jadwal'),
		array('header', 'INFORMASI'),
		array('admin/pengumuman', 'fa-bullhorn',         'Pengumuman'),
	),
	'dosen' => array(
		array('header', 'PERKULIAHAN'),
		array('dosen/mengajar',   'fa-chalkboard',       'Kelas Saya'),
	),
	'mahasiswa' => array(
		array('header', 'PERKULIAHAN'),
		array('mahasiswa/presensi',  'fa-qrcode',        'Isi Presensi'),
		array('mahasiswa/jadwal',    'fa-calendar-week', 'Jadwal Kuliah'),
		array('mahasiswa/kehadiran', 'fa-chart-pie',     'Rekap Kehadiran'),
	),
);
$role_label = array('admin' => 'Bagian Akademik', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa');
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?> | SIAKAD Presensi</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700&display=swap">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/siakad.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Menu"><i class="fas fa-bars"></i></a></li>
			<li class="nav-item d-none d-sm-inline-block"><span class="nav-link text-muted">Tahun Ajaran 2026/2027 Ganjil</span></li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item d-none d-md-block"><span class="nav-link text-muted"><i class="far fa-calendar mr-1"></i><?= tanggal_indo(date('Y-m-d'), TRUE) ?></span></li>
			<li class="nav-item dropdown">
				<a class="nav-link" data-toggle="dropdown" href="#"><i class="far fa-user-circle mr-1"></i><?= html_escape($me->nama) ?></a>
				<div class="dropdown-menu dropdown-menu-right">
					<span class="dropdown-item-text small text-muted"><?= $role_label[$me->role] ?> &middot; <?= html_escape($me->username) ?></span>
					<div class="dropdown-divider"></div>
					<a href="<?= site_url('profil') ?>" class="dropdown-item"><i class="fas fa-key mr-2"></i>Ganti password</a>
					<a href="<?= site_url('logout') ?>" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt mr-2"></i>Keluar</a>
				</div>
			</li>
		</ul>
	</nav>

	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<a href="<?= site_url('dashboard') ?>" class="brand-link">
			<span class="brand-image img-circle elevation-3 bg-primary d-inline-flex align-items-center justify-content-center" style="width:33px;height:33px"><i class="fas fa-user-check text-white" style="font-size:15px"></i></span>
			<span class="brand-text font-weight-bold">SIAKAD Presensi</span>
		</a>
		<div class="sidebar">
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="image"><span class="avatar"><?= strtoupper(mb_substr($me->nama, 0, 1)) ?></span></div>
				<div class="info">
					<a href="<?= site_url('profil') ?>" class="d-block text-truncate" style="max-width:160px"><?= html_escape($me->nama) ?></a>
					<small class="text-muted"><?= $role_label[$me->role] ?></small>
				</div>
			</div>
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
					<li class="nav-item">
						<a href="<?= site_url('dashboard') ?>" class="nav-link<?= $aktif('dashboard') ?>"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a>
					</li>
					<?php foreach ($menu[$me->role] as $m): ?>
						<?php if ($m[0] === 'header'): ?>
							<li class="nav-header"><?= $m[1] ?></li>
						<?php else: ?>
							<li class="nav-item">
								<a href="<?= site_url($m[0]) ?>" class="nav-link<?= $aktif($m[0]) ?>"><i class="nav-icon fas <?= $m[1] ?>"></i><p><?= $m[2] ?></p></a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
					<li class="nav-header">AKUN</li>
					<li class="nav-item"><a href="<?= site_url('profil') ?>" class="nav-link<?= $aktif('profil') ?>"><i class="nav-icon fas fa-key"></i><p>Ganti Password</p></a></li>
					<li class="nav-item"><a href="<?= site_url('logout') ?>" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Keluar</p></a></li>
				</ul>
			</nav>
		</div>
	</aside>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<h1 class="m-0 text-dark"><?= html_escape($title) ?></h1>
			</div>
		</div>
		<section class="content">
			<div class="container-fluid">
				<?php if ($flash = $this->session->flashdata('flash')): ?>
					<div class="alert alert-<?= $flash['type'] ?> alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-label="Tutup">&times;</button>
						<?= $flash['message'] ?>
					</div>
				<?php endif; ?>
				<?= $content ?>
			</div>
		</section>
	</div>

	<footer class="main-footer small">
		<strong>SIAKAD Presensi</strong> &middot; Latihan 2 PBKK &middot; CodeIgniter <?= CI_VERSION ?>
		<div class="float-right d-none d-sm-inline">Dimuat {elapsed_time} detik</div>
	</footer>
</div>

<!-- Modal hapus serbaguna: tombol dengan data-hapus-url & data-hapus-nama -->
<div class="modal fade" id="modalHapus" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<?= form_open('', array('class' => 'modal-content', 'id' => 'formHapus')) ?>
			<div class="modal-header bg-danger">
				<h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi hapus</h5>
				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">Hapus <b id="hapusNama"></b>? <span id="hapusCatatan" class="text-muted"></span></div>
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
		$('#hapusCatatan').text(b.data('hapus-catatan') || '');
	});
	// hitung mundur untuk elemen [data-countdown="YYYY-mm-dd HH:ii:ss"]
	document.querySelectorAll('[data-countdown]').forEach(function (el) {
		var end = new Date(el.dataset.countdown.replace(' ', 'T')).getTime();
		(function tick() {
			var s = Math.max(0, Math.round((end - Date.now()) / 1000));
			el.textContent = String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
			if (s > 0) setTimeout(tick, 1000); else el.textContent = 'waktu habis';
		})();
	});
</script>
</body>
</html>
