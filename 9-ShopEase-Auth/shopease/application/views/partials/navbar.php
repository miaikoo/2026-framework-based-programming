<?php $seg = $this->uri->segment(1); ?>
<nav class="navbar navbar-expand-lg nav-se sticky-top py-3">
	<div class="container">
		<a class="brand" href="<?= base_url() ?>"><span class="brand-mark"><span></span></span>ShopEase</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama" aria-controls="menuUtama" aria-expanded="false" aria-label="Buka menu">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="menuUtama">
			<ul class="navbar-nav ms-lg-4 me-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle<?= $seg === 'kategori' ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Kategori</a>
					<ul class="dropdown-menu">
						<?php foreach ($menu_kategori as $k): ?>
							<li><a class="dropdown-item" href="<?= site_url('kategori/'.$k->slug) ?>"><?= $k->ikon ?> <?= html_escape($k->nama) ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li class="nav-item"><a class="nav-link" href="<?= base_url() ?>#promo">Promo</a></li>
				<li class="nav-item"><a class="nav-link<?= $seg === 'cara-belanja' ? ' active' : '' ?>" href="<?= site_url('cara-belanja') ?>">Cara belanja</a></li>
				<li class="nav-item"><a class="nav-link<?= $seg === 'tentang' ? ' active' : '' ?>" href="<?= site_url('tentang') ?>">Tentang</a></li>
			</ul>
			<form class="d-flex my-3 my-lg-0 me-lg-4" role="search" method="get" action="<?= site_url('cari') ?>">
				<label class="visually-hidden" for="q">Cari miniatur</label>
				<input class="form-control search-se" type="search" id="q" name="q" placeholder="Cari lokomotif, vespa, 1:87…" value="<?= $seg === 'cari' ? html_escape((string) $this->input->get('q')) : '' ?>">
			</form>
			<div class="d-flex align-items-center gap-3 flex-wrap">
				<a class="cart-link" href="<?= site_url('keranjang') ?>">
					Keranjang <span class="cart-count"><?= (int) $jumlah_keranjang ?></span>
				</a>
				<?php if ( ! $pengguna): ?>
					<a class="nav-masuk" href="<?= site_url('login') ?>">Masuk</a>
					<a class="btn btn-se btn-se-sm" href="<?= site_url('register') ?>">Daftar</a>
				<?php else: ?>
					<div class="dropdown">
						<button class="akun-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<span class="inisial" aria-hidden="true"><?= html_escape(mb_strtoupper(mb_substr($pengguna->name, 0, 1))) ?></span>
							<span class="d-none d-xl-inline"><?= html_escape(strtok($pengguna->name, ' ')) ?></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li class="px-3 py-2">
								<div class="fw-semibold"><?= html_escape($pengguna->name) ?></div>
								<div class="small text-steel mb-1"><?= html_escape($pengguna->email) ?></div>
								<?= badge_peran($pengguna->role) ?>
							</li>
							<li><hr class="dropdown-divider"></li>
							<?php if ($pengguna->role === 'admin'): ?>
								<li><a class="dropdown-item" href="<?= site_url('admin') ?>">Dashboard admin</a></li>
								<li><a class="dropdown-item" href="<?= site_url('admin/pengguna') ?>">Kelola pengguna</a></li>
							<?php endif; ?>
							<?php if (in_array($pengguna->role, array('admin', 'operator'), TRUE)): ?>
								<li><a class="dropdown-item" href="<?= site_url('operator') ?>">Dashboard operator</a></li>
							<?php endif; ?>
							<li><a class="dropdown-item" href="<?= site_url('akun') ?>">Akun saya</a></li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<?= form_open('logout', array('class' => 'px-3')) ?>
									<button type="submit" class="btn btn-link p-0 text-danger text-decoration-none">Keluar</button>
								<?= form_close() ?>
							</li>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</nav>
