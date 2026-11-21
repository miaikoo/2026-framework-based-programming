<section id="terbaru">
	<div class="container">
		<div class="section-head">
			<div>
				<h2>Baru masuk etalase</h2>
				<p>Miniatur yang datang dalam dua minggu terakhir.</p>
			</div>
		</div>
		<div class="row g-4">
			<?php foreach ($terbaru as $p): ?>
				<div class="col-6 col-lg-3"><?php $this->load->view('partials/kartu_produk', array('p' => $p)); ?></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
