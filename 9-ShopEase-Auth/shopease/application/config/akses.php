<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Peta hak akses ShopEase
| -------------------------------------------------------------------
| CodeIgniter 3 tidak punya "Filter" seperti CI4. Sebagai gantinya, hook
| Penjaga_akses (application/hooks/Penjaga_akses.php) membaca peta ini
| SEBELUM method controller dijalankan.
|
| Kunci  = awalan URI (segmen pertama setelah base URL).
| Nilai  = 'login'                      -> semua peran yang sudah login
|          array('admin', 'operator')   -> hanya peran tersebut
|
| URI yang tidak tercantum bersifat publik (landing page, katalog, keranjang).
*/
$config['akses'] = array(
	'akun'     => 'login',
	'operator' => array('operator', 'admin'),   // admin juga boleh membuka halaman operator
	'admin'    => array('admin'),
);

/*
| Halaman khusus tamu. Pengguna yang sudah login diarahkan ke beranda perannya.
*/
$config['khusus_tamu'] = array('login', 'register');

/*
| Halaman awal setiap peran setelah login.
*/
$config['beranda_peran'] = array(
	'admin'    => 'admin',
	'operator' => 'operator',
	'customer' => 'akun',
);

/*
| Pembatasan percobaan login: BATAS kali gagal dalam JENDELA menit untuk
| email + IP yang sama akan mengunci login selama KUNCI menit.
*/
$config['login_batas_gagal']   = 5;
$config['login_jendela_menit'] = 15;
$config['login_kunci_menit']   = 5;

/*
| Umur cookie "Ingat saya" (hari).
*/
$config['ingat_saya_hari'] = 30;
