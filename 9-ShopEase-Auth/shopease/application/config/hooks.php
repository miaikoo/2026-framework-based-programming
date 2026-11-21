<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

/*
| Penjaga akses: berjalan setelah controller dibuat tetapi sebelum method-nya
| dipanggil (mirip Filter "before" di CodeIgniter 4).
*/
$hook['post_controller_constructor'][] = array(
	'class'    => 'Penjaga_akses',
	'function' => 'periksa',
	'filename' => 'Penjaga_akses.php',
	'filepath' => 'hooks',
);
