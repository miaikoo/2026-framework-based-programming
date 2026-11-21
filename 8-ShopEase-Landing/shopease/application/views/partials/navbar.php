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
				<input class="form-control search-se" type="search" id="q" name="q" placeholder="Cari lokomotif, vespa, 1:87…" value="<?= html_escape((string) $this->input->get('q')) ?>">
			</form>
			<a class="cart-link" href="<?= site_url('keranjang') ?>">
				Keranjang <span class="cart-count"><?= (int) $jumlah_keranjang ?></span>
			</a>
		</div>
	</div>
</nav>
