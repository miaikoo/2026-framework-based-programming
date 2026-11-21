<?php
/**
 * Landing page ShopEase. Setiap section adalah view terpisah di views/home/sections/
 * sehingga urutan section mudah diubah dan tiap bagian mudah dirawat.
 * Semua data ($unggulan, $terbaru, $promo, ...) disiapkan oleh controller Home::index().
 */
$this->load->view('home/sections/hero');
$this->load->view('home/sections/kategori');
if ($promo) $this->load->view('home/sections/promo');
$this->load->view('home/sections/terbaru');
$this->load->view('home/sections/terlaris');
$this->load->view('home/sections/cara_belanja');
$this->load->view('home/sections/testimoni');
$this->load->view('home/sections/layanan');
