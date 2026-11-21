<?php
$err = function ($f) { return form_error($f) ? ' is-invalid' : ''; };
$msg = function ($f) { return form_error($f, '<div class="invalid-feedback d-block">', '</div>'); };
?>
<div class="container auth-wrap">
	<div class="auth-grid">
		<div class="auth-form">
			<h1>Daftar</h1>
			<p class="text-steel">Sudah punya akun? <a href="<?= site_url('login') ?>">Masuk</a></p>

			<?php if (validation_errors()): ?>
				<div class="pesan pesan-galat" role="alert">Periksa lagi kolom yang ditandai merah.</div>
			<?php endif; ?>

			<?= form_open('register', array('novalidate' => 'novalidate')) ?>
				<div class="mb-3">
					<label class="form-label" for="name">Nama lengkap</label>
					<input class="form-control<?= $err('name') ?>" id="name" name="name" value="<?= set_value('name') ?>" autocomplete="name" autofocus>
					<?= $msg('name') ?>
				</div>
				<div class="mb-3">
					<label class="form-label" for="email">Email</label>
					<input class="form-control<?= $err('email') ?>" type="email" id="email" name="email" value="<?= set_value('email') ?>" autocomplete="email">
					<?= $msg('email') ?>
				</div>
				<div class="row g-3 mb-3">
					<div class="col-sm-6">
						<label class="form-label" for="password">Password</label>
						<div class="input-sandi">
							<input class="form-control<?= $err('password') ?>" type="password" id="password" name="password" autocomplete="new-password" aria-describedby="sandiBantu">
							<button type="button" class="lihat" data-lihat="password" aria-pressed="false">Lihat</button>
						</div>
						<div id="sandiBantu" class="form-text">Minimal 8 karakter, berisi huruf dan angka.</div>
						<?= $msg('password') ?>
					</div>
					<div class="col-sm-6">
						<label class="form-label" for="password_confirm">Ulangi password</label>
						<input class="form-control<?= $err('password_confirm') ?>" type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
						<?= $msg('password_confirm') ?>
					</div>
				</div>
				<fieldset class="opsional">
					<legend>Untuk pengiriman <span class="text-steel fw-normal">(boleh diisi nanti)</span></legend>
					<div class="mb-3">
						<label class="form-label" for="phone">Nomor HP</label>
						<input class="form-control<?= $err('phone') ?>" id="phone" name="phone" value="<?= set_value('phone') ?>" inputmode="tel" placeholder="08xxxxxxxxxx" autocomplete="tel">
						<?= $msg('phone') ?>
					</div>
					<div class="mb-1">
						<label class="form-label" for="address">Alamat</label>
						<textarea class="form-control<?= $err('address') ?>" id="address" name="address" rows="2" autocomplete="street-address"><?= set_value('address') ?></textarea>
						<?= $msg('address') ?>
					</div>
				</fieldset>
				<button type="submit" class="btn btn-se w-100 py-2 mt-4">Buat akun</button>
				<p class="small text-steel mt-2 mb-0">Akun baru terdaftar sebagai customer. Peran operator dan admin hanya bisa diberikan oleh admin.</p>
			<?= form_close() ?>
		</div>
		<?php $this->load->view('auth/_panel', array('mode' => 'register')); ?>
	</div>
</div>
