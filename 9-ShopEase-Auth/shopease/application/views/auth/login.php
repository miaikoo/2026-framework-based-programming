<div class="container auth-wrap">
	<div class="auth-grid">
		<div class="auth-form">
			<h1>Masuk</h1>
			<p class="text-steel">Belum punya akun? <a href="<?= site_url('register') ?>">Daftar gratis</a></p>

			<?php if ($galat): ?>
				<div class="pesan pesan-galat" role="alert"><?= html_escape($galat) ?></div>
			<?php endif; ?>

			<?= form_open('login', array('novalidate' => 'novalidate')) ?>
				<div class="mb-3">
					<label class="form-label" for="email">Email</label>
					<input class="form-control<?= form_error('email') ? ' is-invalid' : '' ?>" type="email" id="email" name="email"
						value="<?= html_escape($email) ?>" autocomplete="username" <?= $email ? '' : 'autofocus' ?>>
					<?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
				</div>
				<div class="mb-3">
					<label class="form-label" for="password">Password</label>
					<div class="input-sandi">
						<input class="form-control<?= form_error('password') ? ' is-invalid' : '' ?>" type="password" id="password" name="password"
							autocomplete="current-password" <?= $email ? 'autofocus' : '' ?>>
						<button type="button" class="lihat" data-lihat="password" aria-pressed="false">Lihat</button>
					</div>
					<?= form_error('password', '<div class="invalid-feedback d-block">', '</div>') ?>
				</div>
				<div class="form-check mb-4">
					<input class="form-check-input" type="checkbox" value="1" id="ingat" name="ingat" <?= $this->input->post('ingat') ? 'checked' : '' ?>>
					<label class="form-check-label" for="ingat">Ingat saya di perangkat ini selama <?= $this->config->item('ingat_saya_hari') ?> hari</label>
				</div>
				<button type="submit" class="btn btn-se w-100 py-2">Masuk</button>
			<?= form_close() ?>

			<details class="akun-demo">
				<summary>Akun contoh untuk mencoba</summary>
				<table class="table table-sm mb-0 mt-2">
					<tr><td>Admin</td><td><code>admin@shopease.test</code></td></tr>
					<tr><td>Operator</td><td><code>operator@shopease.test</code></td></tr>
					<tr><td>Customer</td><td><code>raka@shopease.test</code></td></tr>
				</table>
				<p class="small text-steel mt-2 mb-0">Password semua akun: <code>shopease123</code></p>
			</details>
		</div>
		<?php $this->load->view('auth/_panel', array('mode' => 'login')); ?>
	</div>
</div>
