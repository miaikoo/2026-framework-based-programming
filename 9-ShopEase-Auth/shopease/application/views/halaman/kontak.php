<div class="container page-head pb-5">
	<div class="breadcrumb-se"><a href="<?= base_url() ?>">Beranda</a> / Kontak</div>
	<h1 style="font-size:clamp(2rem,3.6vw,2.8rem)"><?= html_escape($judul) ?></h1>
	<p class="text-steel measure">Tanya stok, ongkos kirim, atau status pesanan. Pesan WhatsApp dibalas pada jam kerja.</p>
	<dl class="spesifikasi fs-5">
		<dt>WhatsApp</dt><dd><?= $layanan->cs['wa'] ?></dd>
		<dt>Email</dt><dd><?= $layanan->cs['email'] ?></dd>
		<dt>Jam kerja</dt><dd><?= $layanan->cs['jam'] ?></dd>
		<dt>Gudang</dt><dd><?= html_escape($layanan->alamat) ?></dd>
	</dl>
	<p class="small text-steel">Kontak di atas adalah data contoh untuk keperluan tugas.</p>
</div>
