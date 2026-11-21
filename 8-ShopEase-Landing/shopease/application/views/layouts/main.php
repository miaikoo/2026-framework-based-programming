<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= html_escape($title) ?></title>
	<meta name="description" content="ShopEase: toko online miniatur kereta, kapal, pesawat, dan kendaraan tradisional dari Yogyakarta.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/css/shopease.css') ?>" rel="stylesheet">
</head>
<body>
<?php $this->load->view('partials/navbar'); ?>

<main id="isi">
	<?= $content ?>
</main>

<?php $this->load->view('partials/footer'); ?>

<?php if ($notif = $this->session->flashdata('notif')): ?>
	<div class="toast-se" role="status">
		<?= html_escape($notif) ?> <a href="<?= site_url('keranjang') ?>">Lihat keranjang</a>
	</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	// hitung mundur promo: elemen [data-selesai] berisi waktu akhir, anak [data-unit] diisi angka
	document.querySelectorAll('[data-selesai]').forEach(function (box) {
		var akhir = new Date(box.dataset.selesai.replace(' ', 'T')).getTime();
		function tick() {
			var s = Math.max(0, Math.floor((akhir - Date.now()) / 1000));
			var v = { hari: Math.floor(s / 86400), jam: Math.floor(s % 86400 / 3600), menit: Math.floor(s % 3600 / 60) };
			box.querySelectorAll('[data-unit]').forEach(function (el) { el.textContent = String(v[el.dataset.unit]).padStart(2, '0'); });
		}
		tick(); setInterval(tick, 30000);
	});
	// sembunyikan notifikasi keranjang setelah beberapa detik
	var t = document.querySelector('.toast-se');
	if (t) setTimeout(function () { t.remove(); }, 6000);
</script>
</body>
</html>
