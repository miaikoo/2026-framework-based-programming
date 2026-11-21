<?php // pesan flashdata: success / warning / danger (isi pesan dibuat di controller) ?>
<?php foreach (array('success', 'warning', 'danger') as $type): ?>
	<?php if ($msg = $this->session->flashdata($type)): ?>
		<div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
			<?= $msg ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
		</div>
	<?php endif; ?>
<?php endforeach; ?>
