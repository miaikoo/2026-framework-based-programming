<?php $this->load->view('home/_header'); ?>

<header class="hero py-5 mb-4">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-7">
				<h1 class="display-5 fw-bold">Buah segar, dipetik hari ini.</h1>
				<p class="lead mb-4">Dari kebun petani lokal sampai buah impor pilihan, semua tersedia di Tokobuah.</p>
				<form class="d-flex gap-2" method="get" action="<?= base_url() ?>">
					<input class="form-control form-control-lg" name="q" value="<?= html_escape($keyword) ?>" placeholder="Cari buah, mis. jeruk">
					<?php if ($category): ?><input type="hidden" name="kategori" value="<?= html_escape($category) ?>"><?php endif; ?>
					<button class="btn btn-warning btn-lg px-4" type="submit"><i class="fas fa-search"></i></button>
				</form>
			</div>
			<div class="col-lg-5 d-none d-lg-block text-center" style="font-size:7rem">🍍🍓🍊</div>
		</div>
	</div>
</header>

<main class="container">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
		<div class="btn-group">
			<?php foreach (array('' => 'Semua', 'Lokal' => 'Lokal', 'Impor' => 'Impor') as $val => $label): ?>
				<a class="btn btn-sm <?= (string) $category === $val ? 'btn-success' : 'btn-outline-success' ?>"
					href="<?= base_url().'?'.http_build_query(array_filter(array('q' => $keyword, 'kategori' => $val))) ?>"><?= $label ?></a>
			<?php endforeach; ?>
		</div>
		<span class="text-muted small">
			<?= count($products) ?> produk<?= $keyword ? ' untuk "<b>'.html_escape($keyword).'</b>"' : '' ?>
		</span>
	</div>

	<?php if ( ! $products): ?>
		<div class="alert alert-light border text-center py-5">
			<div class="fs-1">🧺</div>
			Buah yang dicari belum tersedia. <a href="<?= base_url() ?>">Lihat semua produk</a>
		</div>
	<?php endif; ?>

	<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
		<?php foreach ($products as $p): ?>
			<div class="col">
				<div class="card h-100 border-0 shadow-sm product-card">
					<img src="<?= base_url('upload/product/'.$p->image) ?>" class="card-img-top" alt="<?= html_escape($p->name) ?>">
					<div class="card-body">
						<span class="badge <?= $p->category === 'Impor' ? 'bg-info text-dark' : 'bg-success' ?> mb-2"><?= $p->category ?></span>
						<h5 class="card-title mb-1">
							<a class="stretched-link text-decoration-none text-dark" href="<?= site_url('produk/'.$p->product_id) ?>"><?= html_escape($p->name) ?></a>
						</h5>
						<div class="price"><?= rupiah($p->price) ?> <small class="text-muted fw-normal">/ <?= $p->unit ?></small></div>
					</div>
					<div class="card-footer bg-white border-0 small <?= $p->stock < 20 ? 'text-danger' : 'text-muted' ?>">
						<i class="fas fa-box me-1"></i><?= $p->stock > 0 ? 'Stok '.$p->stock.' '.$p->unit : 'Stok habis' ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</main>

<?php $this->load->view('home/_footer'); ?>
