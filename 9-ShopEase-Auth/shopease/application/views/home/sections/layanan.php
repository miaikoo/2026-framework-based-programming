<?php $nl = $this->session->flashdata('newsletter'); $terdaftar = $this->session->userdata('pelanggan_newsletter'); ?>
<section id="layanan">
	<div class="container">
		<div class="row g-5 align-items-start">
			<div class="col-lg-6">
				<h2 class="mb-4">Pengiriman, pembayaran, dan bantuan</h2>
				<dl class="layanan">
					<dt>Kurir</dt>
					<dd><?php foreach ($layanan->kurir as $k): ?><span class="chip"><?= html_escape($k) ?></span><?php endforeach; ?><br>Ongkos kirim dihitung otomatis dari kota tujuan.</dd>
					<dt>Transfer bank</dt>
					<dd><?php foreach ($layanan->bank as $b): ?><span class="chip"><?= html_escape($b) ?></span><?php endforeach; ?><br>Setelah transfer, konfirmasi dengan ID pesanan dan jumlah yang dibayar.</dd>
					<dt>Customer service</dt>
					<dd>WhatsApp <?= $layanan->cs['wa'] ?>, <?= $layanan->cs['jam'] ?>. <a href="<?= site_url('kontak') ?>">Kontak lengkap</a></dd>
				</dl>
			</div>
			<div class="col-lg-6" id="newsletter">
				<div class="newsletter">
					<h3 class="mb-2">Kabari saya kalau ada diskon</h3>
					<p class="text-steel">Satu email setiap ada promo periode baru. Tidak ada email lain.</p>
					<?php if ($nl): ?>
						<div class="alert <?= $nl['ok'] ? 'alert-success' : 'alert-danger' ?> py-2" role="status"><?= html_escape($nl['pesan']) ?></div>
					<?php endif; ?>
					<?php if ( ! $terdaftar || ($nl && ! $nl['ok'])): ?>
						<?= form_open('langganan', array('class' => 'd-flex flex-wrap gap-2', 'novalidate' => 'novalidate')) ?>
							<label for="email" class="visually-hidden">Email</label>
							<input type="email" id="email" name="email" class="form-control flex-grow-1" style="min-width:220px" placeholder="nama@email.com" required>
							<button type="submit" class="btn btn-se">Daftar</button>
						<?= form_close() ?>
					<?php elseif ( ! $nl): ?>
						<p class="mb-0">Email <b><?= html_escape($terdaftar) ?></b> sudah terdaftar.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
