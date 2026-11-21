<?php
// menu aktif ditentukan dari segmen URL ke-2: /admin/<segmen>
$active = $this->uri->segment(2) ?: 'overview';
$menu = function ($key) use ($active) { return $active === $key ? ' active' : ''; };
?>
<div id="layoutSidenav_nav">
	<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			<div class="nav">
				<div class="sb-sidenav-menu-heading">Utama</div>
				<a class="nav-link<?= $menu('overview') ?>" href="<?= site_url('admin') ?>">
					<div class="sb-nav-link-icon"><i class="fas fa-gauge-high"></i></div>
					Dashboard
				</a>

				<div class="sb-sidenav-menu-heading">Katalog</div>
				<a class="nav-link<?= $menu('products') ?>" href="<?= site_url('admin/products') ?>">
					<div class="sb-nav-link-icon"><i class="fas fa-apple-whole"></i></div>
					Produk
				</a>
				<a class="nav-link" href="<?= site_url('admin/products/add') ?>">
					<div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
					Tambah Produk
				</a>

				<div class="sb-sidenav-menu-heading">Lainnya</div>
				<a class="nav-link" href="<?= base_url() ?>" target="_blank">
					<div class="sb-nav-link-icon"><i class="fas fa-store"></i></div>
					Halaman Toko
				</a>
				<a class="nav-link" href="<?= site_url('logout') ?>">
					<div class="sb-nav-link-icon"><i class="fas fa-right-from-bracket"></i></div>
					Logout
				</a>
			</div>
		</div>
		<div class="sb-sidenav-footer">
			<div class="small">Masuk sebagai:</div>
			<?= html_escape($user->username) ?> (<?= html_escape($user->role) ?>)
		</div>
	</nav>
</div>
