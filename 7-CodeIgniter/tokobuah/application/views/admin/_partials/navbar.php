<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
	<a class="navbar-brand ps-3" href="<?= site_url('admin') ?>">🍉 Tokobuah Admin</a>
	<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" type="button" aria-label="Buka/tutup menu">
		<i class="fas fa-bars"></i>
	</button>

	<ul class="navbar-nav ms-auto me-3 me-lg-4">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				<i class="fas fa-user-circle fa-fw"></i> <?= html_escape($user->full_name) ?>
			</a>
			<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
				<li><span class="dropdown-item-text small text-muted">Role: <?= html_escape($user->role) ?></span></li>
				<li><a class="dropdown-item" href="<?= base_url() ?>" target="_blank"><i class="fas fa-store fa-fw me-1"></i> Lihat toko</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>"><i class="fas fa-sign-out-alt fa-fw me-1"></i> Logout</a></li>
			</ul>
		</li>
	</ul>
</nav>
