<div class="container page-head pb-5">
	<div class="row g-5 align-items-center">
		<div class="col-lg-6">
			<p class="display-font fw-bold" style="font-size:5rem;line-height:1;color:var(--steel)">404</p>
			<h1 style="font-size:clamp(2rem,3.6vw,2.6rem)">Halaman ini tidak ada di etalase</h1>
			<p class="text-steel measure">Alamatnya mungkin salah ketik atau produknya sudah tidak dijual. Coba cari miniatur lain atau kembali ke beranda.</p>
			<form class="d-flex gap-2 mt-3" method="get" action="<?= site_url('cari') ?>">
				<label class="visually-hidden" for="q404">Cari miniatur</label>
				<input class="form-control search-se" id="q404" name="q" placeholder="Cari miniatur">
				<button type="submit" class="btn btn-se">Cari</button>
			</form>
			<a class="d-inline-block mt-3" href="<?= base_url() ?>">Kembali ke beranda</a>
		</div>
		<div class="col-lg-6">
			<h2 class="h5 mb-3">Mungkin kamu mencari</h2>
			<div class="row g-3">
				<?php foreach ($saran as $p): ?>
					<div class="col-4"><?php $this->load->view('partials/kartu_produk', array('p' => $p)); ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
