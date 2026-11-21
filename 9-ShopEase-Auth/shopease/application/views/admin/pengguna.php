<?php $kembali = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''; ?>
<div class="container page-head pb-5">
	<?php $this->load->view('partials/panel_nav'); ?>
	<h1 class="mt-4" style="font-size:clamp(1.8rem,3vw,2.4rem)">Kelola pengguna</h1>
	<p class="text-steel">Ubah peran atau hapus akun. Perubahan peran langsung berlaku tanpa pengguna perlu login ulang.</p>

	<form method="get" class="d-flex flex-wrap gap-2 align-items-center mb-3">
		<label class="visually-hidden" for="cariPengguna">Cari pengguna</label>
		<input class="form-control search-se" id="cariPengguna" name="q" value="<?= html_escape($q) ?>" placeholder="Cari nama, email, atau HP" style="max-width:320px">
		<?php if ($peran): ?><input type="hidden" name="peran" value="<?= html_escape($peran) ?>"><?php endif; ?>
		<button type="submit" class="btn btn-se btn-se-sm">Cari</button>
		<div class="filter-peran ms-lg-auto">
			<?php foreach (array('' => 'Semua', 'customer' => 'Customer', 'operator' => 'Operator', 'admin' => 'Admin') as $v => $l): ?>
				<?php $n = $v === '' ? array_sum($per_peran) : $per_peran[$v]; ?>
				<a class="<?= (string) $peran === $v ? 'aktif' : '' ?>" href="<?= site_url('admin/pengguna').'?'.http_build_query(array_filter(array('peran' => $v, 'q' => $q))) ?>"><?= $l ?> <span><?= $n ?></span></a>
			<?php endforeach; ?>
		</div>
	</form>

	<div class="kotak p-0 overflow-hidden">
		<table class="table align-middle mb-0 tabel-pengguna">
			<thead><tr><th>Pengguna</th><th>Kontak</th><th>Login terakhir</th><th>Peran</th><th><span class="visually-hidden">Hapus</span></th></tr></thead>
			<tbody>
				<?php foreach ($daftar as $p): ?>
					<?php $saya = (int) $p->id_user === (int) $pengguna->id_user; ?>
					<tr>
						<td data-label="Pengguna">
							<b><?= html_escape($p->name) ?></b><?= $saya ? ' <span class="text-steel small">(kamu)</span>' : '' ?>
							<small class="d-block text-steel"><?= html_escape($p->email) ?></small>
						</td>
						<td data-label="Kontak" class="small"><?= $p->phone ? html_escape($p->phone) : '<span class="text-steel">—</span>' ?></td>
						<td data-label="Login terakhir" class="small"><?= waktu_lalu($p->last_login_at) ?></td>
						<td data-label="Peran">
							<?php if ($saya): ?>
								<?= badge_peran($p->role) ?>
							<?php else: ?>
								<?= form_open('admin/peran/'.$p->id_user, array('class' => 'd-flex gap-1')) ?>
									<input type="hidden" name="kembali" value="<?= html_escape($kembali) ?>">
									<label class="visually-hidden" for="role<?= $p->id_user ?>">Peran <?= html_escape($p->name) ?></label>
									<select id="role<?= $p->id_user ?>" name="role" class="form-select form-select-sm w-auto">
										<?php foreach (User_model::PERAN as $r): ?><option value="<?= $r ?>" <?= $p->role === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option><?php endforeach; ?>
									</select>
									<button type="submit" class="btn btn-se btn-se-line btn-se-sm">Ubah</button>
								<?= form_close() ?>
							<?php endif; ?>
						</td>
						<td class="text-end">
							<?php if ( ! $saya): ?>
								<?= form_open('admin/hapus/'.$p->id_user, array('onsubmit' => "return confirm('Hapus akun ".html_escape(addslashes($p->name))."? Tindakan ini tidak bisa dibatalkan.')")) ?>
									<input type="hidden" name="kembali" value="<?= html_escape($kembali) ?>">
									<button type="submit" class="btn btn-link btn-sm text-danger">Hapus</button>
								<?= form_close() ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php if ( ! $daftar): ?><tr><td colspan="5" class="text-steel p-4">Tidak ada pengguna yang cocok. Coba kata kunci lain atau pilih "Semua".</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
