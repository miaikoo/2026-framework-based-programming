<div class="row">
	<div class="col-md-5">
		<div class="card card-primary card-outline">
			<div class="card-body box-profile text-center">
				<span class="avatar" style="width:80px;height:80px;font-size:2rem"><?= strtoupper(mb_substr($me->nama, 0, 1)) ?></span>
				<h3 class="profile-username mt-2"><?= html_escape($me->nama) ?></h3>
				<p class="text-muted"><?= ucfirst($me->role) ?></p>
				<ul class="list-group list-group-unbordered text-left">
					<li class="list-group-item"><b>Username</b> <span class="float-right"><?= html_escape($me->username) ?></span></li>
					<li class="list-group-item"><b>Login terakhir</b> <span class="float-right"><?= $me->last_login ? date('d/m/Y H:i', strtotime($me->last_login)) : '-' ?></span></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="card">
			<div class="card-header"><h3 class="card-title">Ganti password</h3></div>
			<?= form_open('profil') ?>
				<div class="card-body">
					<?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>
					<div class="form-group">
						<label>Password lama</label>
						<input type="password" name="password_lama" class="form-control">
					</div>
					<div class="form-group">
						<label>Password baru <small class="text-muted">(min. 8 karakter)</small></label>
						<input type="password" name="password_baru" class="form-control">
					</div>
					<div class="form-group">
						<label>Ulangi password baru</label>
						<input type="password" name="konfirmasi" class="form-control">
					</div>
				</div>
				<div class="card-footer"><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button></div>
			<?= form_close() ?>
		</div>
	</div>
</div>
