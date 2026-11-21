<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// ---------------------------------------------------------------
// Routing ShopEase
// ---------------------------------------------------------------
// Halaman utama (landing page)
$route['default_controller'] = 'home';

// Katalog: /kategori/kereta, /cari?q=vespa, /produk/lokomotif-uap
$route['kategori/(:any)'] = 'home/kategori/$1';
$route['cari']            = 'home/cari';
$route['produk/(:any)']   = 'produk/detail/$1';

// Newsletter (form POST di landing page)
$route['langganan'] = 'home/langganan';

// Keranjang belanja (shopping cart)
$route['keranjang']          = 'keranjang/index';
$route['keranjang/tambah']   = 'keranjang/tambah';
$route['keranjang/hapus']    = 'keranjang/hapus';

// Halaman statis: satu controller untuk beberapa URL
$route['(tentang|cara-belanja|kontak)'] = 'halaman/tampil/$1';

// ---------------------------------------------------------------
// Autentikasi (Tugas 9)
// ---------------------------------------------------------------
$route['register'] = 'auth/register';   // GET = form, POST = simpan
$route['login']    = 'auth/login';      // GET = form, POST = periksa
$route['logout']   = 'auth/logout';     // hanya POST

// Halaman yang dijaga (lihat config/akses.php):
//   /akun      semua peran      -> Akun
//   /operator  operator & admin -> Operator
//   /admin     admin            -> Admin (+ /admin/pengguna)

// Halaman 404 buatan sendiri
$route['404_override'] = 'halaman/tidak_ditemukan';
$route['translate_uri_dashes'] = FALSE;
