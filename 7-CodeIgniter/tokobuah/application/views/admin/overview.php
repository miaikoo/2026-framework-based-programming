<?php $this->load->view('admin/_partials/head'); ?>
<?php $this->load->view('admin/_partials/navbar'); ?>
<div id="layoutSidenav">
	<?php $this->load->view('admin/_partials/sidebar'); ?>
	<div id="layoutSidenav_content">
		<main>
			<div class="container-fluid px-4">
				<h1 class="mt-4">Dashboard</h1>
				<?php $this->load->view('admin/_partials/breadcrumb'); ?>
				<?php $this->load->view('admin/_partials/alert'); ?>

				<div class="row">
					<?php
					$cards = array(
						array('bg-primary', 'fa-apple-whole',   'Jenis produk',     $summary->total_produk),
						array('bg-success', 'fa-boxes-stacked', 'Total stok',       number_format($summary->total_stok, 0, ',', '.')),
						array('bg-warning', 'fa-coins',         'Nilai stok',       rupiah($summary->nilai_stok)),
						array('bg-danger',  'fa-triangle-exclamation', 'Stok < 20', $summary->stok_menipis.' produk'),
					);
					foreach ($cards as $c): ?>
						<div class="col-xl-3 col-md-6">
							<div class="card <?= $c[0] ?> text-white mb-4 card-stat">
								<div class="card-body d-flex justify-content-between align-items-center">
									<div>
										<div class="small text-white-50"><?= $c[2] ?></div>
										<div class="fs-4 fw-bold"><?= $c[3] ?></div>
									</div>
									<i class="fas <?= $c[1] ?> icon"></i>
								</div>
								<div class="card-footer d-flex align-items-center justify-content-between">
									<a class="small text-white stretched-link" href="<?= site_url('admin/products') ?>">Lihat produk</a>
									<div class="small text-white"><i class="fas fa-angle-right"></i></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="row">
					<div class="col-xl-7">
						<div class="card mb-4">
							<div class="card-header"><i class="fas fa-chart-bar me-1"></i> Stok per produk</div>
							<div class="card-body"><canvas id="chartStok" height="150"></canvas></div>
						</div>
					</div>
					<div class="col-xl-5">
						<div class="card mb-4">
							<div class="card-header"><i class="fas fa-clock-rotate-left me-1"></i> Produk terbaru</div>
							<ul class="list-group list-group-flush">
								<?php foreach ($latest as $p): ?>
									<li class="list-group-item d-flex align-items-center">
										<img class="thumb me-3" src="<?= base_url('upload/product/'.$p->image) ?>" alt="">
										<div class="flex-grow-1">
											<div class="fw-semibold"><?= html_escape($p->name) ?></div>
											<div class="small text-muted"><?= rupiah($p->price) ?> / <?= $p->unit ?></div>
										</div>
										<span class="badge bg-secondary"><?= $p->stock ?> <?= $p->unit ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="card mb-4">
							<div class="card-body small text-muted">
								<i class="fas fa-users me-1"></i> <?= $total_user ?> akun pengelola terdaftar.
								Anda login sebagai <b><?= html_escape($user->username) ?></b>.
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php $this->load->view('admin/_partials/footer'); ?>
	</div>
</div>
<?php $this->load->view('admin/_partials/js'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
	new Chart(document.getElementById('chartStok'), {
		type: 'bar',
		data: {
			labels: <?= json_encode($chart_label) ?>,
			datasets: [{ label: 'Stok', data: <?= json_encode($chart_stock) ?>, backgroundColor: '#0d6efd', borderRadius: 4 }]
		},
		options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
	});
</script>
</body>
</html>
