<?php
$err = function ($f) { return form_error($f) ? ' is-invalid' : ''; };
$msg = function ($f) { return form_error($f, '<div class="invalid-feedback d-block">', '</div>'); };
$halaman = array(
	'akun'     => array('Akun saya', 'Profil dan password'),
	'operator' => array('Dashboard operator', 'Stok menipis & data pelanggan'),
	'admin'    => array('Dashboard admin', 'Kelola pengguna & log login'),
);
$boleh = function ($kunci) use ($aturan, $u) {
	return $aturan[$kunci] === 'login' || in_array($u->role, $aturan[$kunci], TRUE);
};
?>
<div class="container page-head pb-5">
	<?php $this->load->view('partials/panel_nav'); ?>
	<div class="d-flex flex-wrap align-items-center gap-3 mt-4">
		<span class="inisial inisial-besar" aria-hidden="true"><?= html_escape(mb_strtoupper(mb_substr($u->name, 0, 1))) ?></span>
		<div>
			<h1 class="mb-1" style="font-size:clamp(1.8rem,3vw,2.4rem)"><?= html_escape($u->name) ?></h1>
			<div class="text-steel"><?= html_escape($u->email) ?> <?= badge_peran($u->role) ?></div>
		</div>
	</div>

	<div class="row g-4 mt-2">
		<div class="col-lg-7">
			<section class="kotak">
				<h2 class="h5">Data diri</h2>
				<?= form_open('akun/simpan', array('novalidate' => 'novalidate')) ?>
					<div class="mb-3">
						<label class="form-label" for="name">Nama lengkap</label>
						<input class="form-control<?= $err('name') ?>" id="name" name="name" value="<?= set_value('name', $u->name) ?>">
						<?= $msg('name') ?>
					</div>
					<div class="mb-3">
						<label class="form-label" for="emailRo">Email</label>
						<input class="form-control" id="emailRo" value="<?= html_escape($u->email) ?>" disabled>
						<div class="form-text">Email dipakai untuk masuk dan tidak bisa diubah sendiri.</div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="phone">Nomor HP</label>
						<input class="form-control<?= $err('phone') ?>" id="phone" name="phone" value="<?= set_value('phone', (string) $u->phone) ?>" placeholder="08xxxxxxxxxx" inputmode="tel">
						<?= $msg('phone') ?>
					</div>
					<div class="mb-3">
						<label class="form-label" for="address">Alamat pengiriman</label>
						<textarea class="form-control<?= $err('address') ?>" id="address" name="address" rows="2"><?= set_value('address', (string) $u->address) ?></textarea>
						<?= $msg('address') ?>
					</div>
					<button type="submit" class="btn btn-se">Simpan profil</button>
				<?= form_close() ?>
			</section>

			<section class="kotak mt-4">
				<h2 class="h5">Ganti password</h2>
				<?= form_open('akun/password', array('novalidate' => 'novalidate')) ?>
					<div class="mb-3">
						<label class="form-label" for="password_lama">Password lama</label>
						<input class="form-control<?= $err('password_lama') ?>" type="password" id="password_lama" name="password_lama" autocomplete="current-password">
						<?= $msg('password_lama') ?>
					</div>
					<div class="row g-3 mb-3">
						<div class="col-sm-6">
							<label class="form-label" for="password_baru">Password baru</label>
							<input class="form-control<?= $err('password_baru') ?>" type="password" id="password_baru" name="password_baru" autocomplete="new-password">
							<?= $msg('password_baru') ?>
						</div>
						<div class="col-sm-6">
							<label class="form-label" for="password_ulang">Ulangi password baru</label>
							<input class="form-control<?= $err('password_ulang') ?>" type="password" id="password_ulang" name="password_ulang" autocomplete="new-password">
							<?= $msg('password_ulang') ?>
						</div>
					</div>
					<button type="submit" class="btn btn-se btn-se-line">Ganti password</button>
					<p class="small text-steel mt-2 mb-0">Setelah password diganti, semua perangkat yang diingat akan dikeluarkan.</p>
				<?= form_close() ?>
			</section>
		</div>

		<div class="col-lg-5">
			<section class="kotak">
				<h2 class="h5">Halaman yang bisa kamu buka</h2>
				<ul class="peta-akses">
					<?php foreach ($halaman as $kunci => $h): ?>
						<li class="<?= $boleh($kunci) ? 'boleh' : 'tidak' ?>">
							<span class="status"><?= $boleh($kunci) ? 'Boleh' : 'Tidak' ?></span>
							<span>
								<?php if ($boleh($kunci)): ?><a href="<?= site_url($kunci) ?>"><?= $h[0] ?></a><?php else: ?><?= $h[0] ?><?php endif; ?>
								<small class="d-block text-steel">/<?= $kunci ?>, <?= $h[1] ?></small>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="kotak mt-4">
				<h2 class="h5">Sesi &amp; perangkat</h2>
				<dl class="spesifikasi my-2">
					<dt>Login terakhir</dt><dd><?= waktu_lalu($u->last_login_at) ?></dd>
					<dt>Terdaftar</dt><dd><?= tanggal_id($u->created_at) ?></dd>
					<dt>Perangkat ini</dt><dd><?= $diingat ? 'diingat ("Ingat saya")' : 'sesi biasa' ?></dd>
					<dt>Perangkat diingat</dt><dd><?= $perangkat ?></dd>
				</dl>
				<?php if ($perangkat): ?>
					<?= form_open('akun/lupakan') ?>
						<button type="submit" class="btn btn-se btn-se-line btn-se-sm">Lupakan semua perangkat</button>
					<?= form_close() ?>
				<?php endif; ?>
			</section>
		</div>
	</div>
</div>
