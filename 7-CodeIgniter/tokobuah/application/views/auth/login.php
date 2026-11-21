<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?></title>
	<link href="<?= base_url('assets/sb-admin/styles.css') ?>" rel="stylesheet">
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
	<style>
		body { background: linear-gradient(135deg, #14532d, #65a30d); }
	</style>
</head>
<body>
<div id="layoutAuthentication">
	<div id="layoutAuthentication_content">
		<main>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-5">
						<div class="card shadow-lg border-0 rounded-lg mt-5">
							<div class="card-header text-center">
								<div class="display-6">🍉</div>
								<h3 class="font-weight-light my-2">Login Admin Tokobuah</h3>
							</div>
							<div class="card-body">
								<?php $this->load->view('admin/_partials/alert'); ?>
								<?php if (validation_errors()): ?>
									<div class="alert alert-danger py-2"><?= validation_errors('<div>', '</div>') ?></div>
								<?php endif; ?>

								<?= form_open('login') ?>
									<div class="form-floating mb-3">
										<input class="form-control" id="login" name="login" type="text" placeholder="admin" value="<?= set_value('login') ?>" autofocus>
										<label for="login">Username atau email</label>
									</div>
									<div class="form-floating mb-3">
										<input class="form-control" id="password" name="password" type="password" placeholder="Password">
										<label for="password">Password</label>
									</div>
									<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
										<a class="small" href="<?= base_url() ?>">&larr; Kembali ke toko</a>
										<button class="btn btn-success px-4" type="submit"><i class="fas fa-right-to-bracket me-1"></i> Login</button>
									</div>
								<?= form_close() ?>
							</div>
							<div class="card-footer text-center py-3 small text-muted">
								Akun demo: <code>admin</code> / <code>tokobuah123</code> (admin) &middot; <code>kasir</code> / <code>tokobuah123</code> (staff)
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
