<?php // $crumbs = array('Label' => 'url' atau NULL untuk item terakhir) ?>
<ol class="breadcrumb mb-4">
	<li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Admin</a></li>
	<?php foreach ($crumbs as $label => $url): ?>
		<?php if ($url): ?>
			<li class="breadcrumb-item"><a href="<?= site_url($url) ?>"><?= html_escape($label) ?></a></li>
		<?php else: ?>
			<li class="breadcrumb-item active"><?= html_escape($label) ?></li>
		<?php endif; ?>
	<?php endforeach; ?>
</ol>
