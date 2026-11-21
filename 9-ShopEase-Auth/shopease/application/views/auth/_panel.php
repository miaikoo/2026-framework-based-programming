<?php // Panel kanan halaman masuk/daftar. Variabel: $mode = 'login' | 'register' ?>
<aside class="auth-panel">
	<span class="tag-harga">Akun ShopEase</span>
	<h2 class="mt-4"><?= $mode === 'login' ? 'Lanjutkan koleksimu' : 'Satu akun untuk semua pesanan' ?></h2>
	<ul class="auth-manfaat">
		<li>Simpan alamat pengiriman, tidak perlu mengetik ulang setiap checkout.</li>
		<li>Pantau status pesanan dan konfirmasi pembayaran dari satu halaman.</li>
		<li>Tulis testimoni untuk miniatur yang sudah kamu terima.</li>
	</ul>
	<div class="auth-vitrin" aria-hidden="true">
		<?php foreach (array('lokomotif-uap', 'vespa-klasik', 'kapal-pinisi') as $slug): ?>
			<img src="<?= base_url('assets/img/produk/'.$slug.'.png') ?>" alt="">
		<?php endforeach; ?>
	</div>
</aside>
