<?php $bisa_pesan = ($status === 'dibuka' && $sisa !== 0); ?>
<ol class="breadcrumb bg-transparent px-0">
	<li class="breadcrumb-item"><a href="<?= base_url() ?>">Katalog</a></li>
	<li class="breadcrumb-item"><?= html_escape($batch->nama) ?></li>
	<li class="breadcrumb-item active"><?= html_escape($produk->nama) ?></li>
</ol>

<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-5">
				<img class="img-fluid rounded" src="<?= base_url('upload/produk/'.$produk->gambar) ?>" alt="<?= html_escape($produk->nama) ?>">
			</div>
			<div class="col-md-7">
				<?= badge_batch($status) ?>
				<h2 class="mt-2 mb-1"><?= html_escape($produk->nama) ?></h2>
				<div class="h3 text-purple font-weight-bold mb-3" style="color:#6d28d9"><?= rupiah($produk->harga) ?></div>
				<p><?= nl2br(html_escape((string) $produk->deskripsi)) ?></p>

				<?php if ($sisa !== NULL): ?>
					<div class="mb-3">
						<small class="text-muted">Kuota terbatas: <?= $produk->kuota ?> pcs &middot; terpesan <?= $produk->terpesan ?></small>
						<div class="progress progress-sm"><div class="progress-bar bg-warning" style="width:<?= min(100, $produk->terpesan / $produk->kuota * 100) ?>%"></div></div>
						<small class="<?= $sisa < 10 ? 'text-danger font-weight-bold' : '' ?>">Sisa <?= $sisa ?> pcs</small>
					</div>
				<?php endif; ?>

				<?= form_open('keranjang/tambah') ?>
					<input type="hidden" name="produk_id" value="<?= $produk->id ?>">
					<?php if ($produk->pakai_ukuran): ?>
						<label class="d-block">Ukuran <a href="#" class="small ml-2" data-toggle="collapse" data-target="#sizeChart">lihat size chart</a></label>
						<div class="btn-group btn-group-toggle ukuran-pilih mb-2" data-toggle="buttons">
							<?php foreach (Produk_model::UKURAN as $u): ?>
								<label class="btn btn-outline-secondary"><input type="radio" name="ukuran" value="<?= $u ?>" autocomplete="off"> <?= $u ?></label>
							<?php endforeach; ?>
						</div>
						<div class="collapse" id="sizeChart">
							<table class="table table-sm table-bordered small text-center w-auto">
								<tr class="bg-light"><th>Ukuran</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>XXL</th></tr>
								<tr><th>Lebar (cm)</th><td>48</td><td>50</td><td>53</td><td>56</td><td>59</td></tr>
								<tr><th>Panjang (cm)</th><td>67</td><td>70</td><td>73</td><td>76</td><td>78</td></tr>
							</table>
						</div>
					<?php endif; ?>
					<div class="form-group" style="max-width:160px">
						<label for="qty">Jumlah</label>
						<input id="qty" type="number" name="qty" class="form-control" value="1" min="1" max="<?= Keranjang_belanja::MAKS_QTY ?>">
					</div>
					<?php if ($bisa_pesan): ?>
						<button type="submit" class="btn btn-lg btn-warning"><i class="fas fa-cart-plus mr-1"></i> Masukkan keranjang</button>
					<?php else: ?>
						<button type="button" class="btn btn-lg btn-secondary" disabled><?= $sisa === 0 ? 'Kuota habis' : ($status === 'segera' ? 'PO belum dibuka' : 'PO sudah ditutup') ?></button>
					<?php endif; ?>
				<?= form_close() ?>

				<hr>
				<small class="text-muted">
					<i class="far fa-clock mr-1"></i> PO: <?= tgl($batch->dibuka_at) ?> &ndash; <?= tgl($batch->ditutup_at, TRUE) ?> &middot;
					<i class="fas fa-box-open mr-1"></i> Estimasi ambil <?= tgl($batch->estimasi_ambil) ?>
				</small>
			</div>
		</div>
	</div>
</div>
