<section id="cara-belanja">
	<div class="container">
		<h2>Dari keranjang sampai rumah</h2>
		<p class="text-steel measure">Lima tahap yang sama untuk setiap pesanan. Status pesanan bisa dipantau di riwayat transaksi.</p>
		<ol class="rel">
			<?php foreach ($langkah as $l): ?>
				<li><b><?= html_escape($l->judul) ?></b><p><?= html_escape($l->isi) ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
