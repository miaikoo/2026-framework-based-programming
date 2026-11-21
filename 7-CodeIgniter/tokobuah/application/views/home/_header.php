<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
	<style>
		body { background: #f7faf5; }
		.hero { background: linear-gradient(120deg, #166534, #4d7c0f); color: #fff; }
		.product-card img { aspect-ratio: 4 / 3; object-fit: cover; }
		.product-card { transition: transform .15s ease, box-shadow .15s ease; }
		.product-card:hover { transform: translateY(-3px); box-shadow: 0 .6rem 1.2rem rgba(0,0,0,.08); }
		.price { color: #15803d; font-weight: 700; }
	</style>
</head>
<body>
<nav class="navbar navbar-expand navbar-dark" style="background:#14532d">
	<div class="container">
		<a class="navbar-brand fw-bold" href="<?= base_url() ?>">🍉 Tokobuah</a>
		<div class="navbar-nav ms-auto">
			<a class="nav-link" href="<?= base_url() ?>">Katalog</a>
			<a class="nav-link" href="<?= site_url('login') ?>"><i class="fas fa-lock me-1"></i>Admin</a>
		</div>
	</div>
</nav>
