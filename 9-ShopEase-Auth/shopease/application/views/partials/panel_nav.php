<?php
// Navigasi antarhalaman panel; hanya menampilkan tautan yang boleh dibuka peran pengguna.
$aktif = uri_string();
$tautan = array(array('akun', 'Akun saya', array('customer', 'operator', 'admin')));
if (in_array($pengguna->role, array('operator', 'admin'), TRUE)) $tautan[] = array('operator', 'Dashboard operator', array());
if ($pengguna->role === 'admin') { $tautan[] = array('admin', 'Dashboard admin', array()); $tautan[] = array('admin/pengguna', 'Kelola pengguna', array()); }
?>
<nav class="panel-nav" aria-label="Menu panel">
	<?php foreach ($tautan as $t): ?>
		<a href="<?= site_url($t[0]) ?>"<?= $aktif === $t[0] ? ' class="aktif" aria-current="page"' : '' ?>><?= $t[1] ?></a>
	<?php endforeach; ?>
</nav>
