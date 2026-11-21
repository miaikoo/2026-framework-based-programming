<section id="testimoni" class="bg-white">
	<div class="container">
		<h2 class="mb-4">Kata pelanggan</h2>
		<div class="row g-5">
			<?php foreach ($testimoni as $t): ?>
				<div class="col-md-4">
					<blockquote class="testi">
						<p>“<?= html_escape($t->isi) ?>”</p>
						<footer><b><?= html_escape($t->nama) ?></b>, <?= html_escape($t->kota) ?><br>membeli <?= html_escape($t->produk) ?></footer>
					</blockquote>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
