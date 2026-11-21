<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card card-outline card-warning mt-3">
			<div class="card-header text-center"><h4 class="mb-0">Buat akun pembeli</h4></div>
			<?= form_open('daftar') ?>
				<div class="card-body">
					<div class="form-group">
						<label for="nama">Nama lengkap</label>
						<input id="nama" name="nama" class="form-control<?= invalid('nama') ?>" value="<?= set_value('nama') ?>">
						<?= pesan_error('nama') ?>
					</div>
					<div class="form-row">
						<div class="form-group col-md-7">
							<label for="email">Email</label>
							<input id="email" name="email" type="email" class="form-control<?= invalid('email') ?>" value="<?= set_value('email') ?>">
							<?= pesan_error('email') ?>
						</div>
						<div class="form-group col-md-5">
							<label for="no_wa">No. WhatsApp</label>
							<input id="no_wa" name="no_wa" class="form-control<?= invalid('no_wa') ?>" value="<?= set_value('no_wa') ?>" placeholder="08xxxxxxxxxx">
							<?= pesan_error('no_wa') ?>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="password">Password</label>
							<input id="password" name="password" type="password" class="form-control<?= invalid('password') ?>">
							<?= pesan_error('password') ?>
						</div>
						<div class="form-group col-md-6">
							<label for="konfirmasi">Ulangi password</label>
							<input id="konfirmasi" name="konfirmasi" type="password" class="form-control<?= invalid('konfirmasi') ?>">
							<?= pesan_error('konfirmasi') ?>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-warning btn-block"><i class="fas fa-user-plus mr-1"></i> Daftar</button>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>
