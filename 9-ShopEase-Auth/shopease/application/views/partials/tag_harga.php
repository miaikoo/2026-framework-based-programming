<?php // Label harga gantung. Variabel: $p (produk) ?>
<span class="tag-harga<?= $p->diskon ? ' diskon' : '' ?>">
	<?php if ($p->diskon): ?><s><?= rupiah($p->harga) ?></s><?php endif; ?>
	<?= rupiah($p->harga_akhir) ?>
</span>
