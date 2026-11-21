<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | SIAKAD Presensi</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=swap">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/siakad.css') ?>">
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<span class="h2"><b>SIAKAD</b> Presensi</span>
			<div class="text-muted small">Sistem Informasi Akademik &middot; Presensi Kuliah</div>
		</div>
		<div class="card-body">
			<p class="login-box-msg">Masuk dengan NIM / NIDN</p>

			<?php if ($flash = $this->session->flashdata('flash')): ?>
				<div class="alert alert-<?= $flash['type'] ?> py-2"><?= $flash['message'] ?></div>
			<?php endif; ?>
			<?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

			<?= form_open('login') ?>
				<div class="input-group mb-3">
					<input type="text" name="username" class="form-control" placeholder="NIM / NIDN / admin" value="<?= set_value('username') ?>" autofocus>
					<div class="input-group-append"><div class="input-group-text"><span class="fas fa-id-card"></span></div></div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Password">
					<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
				</div>
				<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt mr-1"></i> Masuk</button>
			<?= form_close() ?>
		</div>
		<div class="card-footer small text-muted">
			<b>Akun demo</b> (password <code>siakad123</code>):<br>
			Admin: <code>admin</code> &middot; Dosen: <code>0011087801</code> &middot; Mahasiswa: <code>2310101001</code>
		</div>
	</div>
</div>
</body>
</html>
