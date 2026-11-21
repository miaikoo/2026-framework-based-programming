<?php $this->load->view('home/_header'); ?>

<main class="container py-4">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>">Katalog</a></li>
			<li class="breadcrumb-item active"><?= html_escape($product->name) ?></li>
		</ol>
	</nav>

	<div class="row g-5">
		<div class="col-md-6">
			<img class="img-fluid rounded-4 shadow-sm" src="<?= base_url('upload/product/'.$product->image) ?>" alt="<?= html_escape($product->name) ?>">
		</div>
		<div class="col-md-6">
			<span class="badge <?= $product->category === 'Impor' ? 'bg-info text-dark' : 'bg-success' ?>"><?= $product->category ?></span>
			<h1 class="mt-2"><?= html_escape($product->name) ?></h1>
			<p class="fs-3 price mb-3"><?= rupiah($product->price) ?> <small class="fs-6 text-muted fw-normal">/ <?= $product->unit ?></small></p>
			<p class="lead"><?= nl2br(html_escape((string) $product->description)) ?></p>

			<table class="table table-sm w-auto">
				<tr><th class="pe-4">Stok</th><td><?= $product->stock ?> <?= $product->unit ?></td></tr>
				<tr><th class="pe-4">Satuan jual</th><td><?= $product->unit ?></td></tr>
				<tr><th class="pe-4">Asal</th><td><?= $product->category === 'Impor' ? 'Buah impor' : 'Petani lokal' ?></td></tr>
			</table>

			<a class="btn btn-outline-success" href="<?= base_url() ?>"><i class="fas fa-arrow-left me-1"></i> Kembali ke katalog</a>
		</div>
	</div>
</main>

<?php $this->load->view('home/_footer'); ?>
