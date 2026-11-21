<div class="row justify-content-center">
	<div class="col-md-5">
		<div class="card card-outline card-warning mt-3">
			<div class="card-header text-center"><h4 class="mb-0">Masuk ke MerchHima</h4></div>
			<div class="card-body">
				<?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>
				<?= form_open('login') ?>
					<div class="form-group">
						<label for="email">Email</label>
						<input id="email" name="email" type="email" class="form-control" value="<?= set_value('email') ?>" autofocus>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input id="password" name="password" type="password" class="form-control">
					</div>
					<button type="submit" class="btn btn-warning btn-block"><i class="fas fa-sign-in-alt mr-1"></i> Masuk</button>
				<?= form_close() ?>
				<p class="text-center mt-3 mb-0">Belum punya akun? <a href="<?= site_url('daftar') ?>">Daftar di sini</a></p>
			</div>
			<div class="card-footer small text-muted">
				Akun demo (password <code>merch123</code>): admin <code>admin@merchhima.test</code>, pembeli <code>aditya@student.kampus.test</code>
			</div>
		</div>
	</div>
</div>
