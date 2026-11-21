<?php $tertinggi = $terlaris ? $terlaris[0]->terjual : 1; ?>
<section id="terlaris" class="bg-white">
	<div class="container">
		<div class="row g-5">
			<div class="col-lg-4">
				<h2>Paling banyak dibeli</h2>
				<p class="text-steel measure">Urutan dihitung dari jumlah unit terjual. Becak dan pesawat penumpang jadi favorit untuk oleh-oleh dan hadiah.</p>
			</div>
			<div class="col-lg-8">
				<ol class="peringkat">
					<?php foreach ($terlaris as $p): ?>
						<li>
							<img src="<?= base_url($p->gambar) ?>" alt="">
							<div>
								<a class="fw-semibold text-decoration-none" href="<?= site_url('produk/'.$p->slug) ?>"><?= html_escape($p->nama) ?></a>
								<div class="small text-steel"><?= $p->terjual ?> terjual, skala <?= $p->skala ?></div>
								<div class="bar" aria-hidden="true"><span style="width:<?= round($p->terjual / $tertinggi * 100) ?>%"></span></div>
							</div>
							<?php $this->load->view('partials/tag_harga', array('p' => $p)); ?>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</div>
</section>
