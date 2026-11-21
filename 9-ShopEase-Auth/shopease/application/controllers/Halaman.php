<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman informasi. Tiga URL (/tentang, /cara-belanja, /kontak) diarahkan ke
 * satu method tampil($slug) lewat satu baris route regex.
 * tidak_ditemukan() dipakai sebagai $route['404_override'].
 */
class Halaman extends MY_Controller
{
	public function tampil($slug = 'tentang')
	{
		$judul = array('tentang' => 'Tentang ShopEase', 'cara-belanja' => 'Cara belanja', 'kontak' => 'Hubungi kami');
		if ( ! isset($judul[$slug]))
		{
			$this->tidak_ada();
		}

		$this->render('halaman/'.str_replace('-', '_', $slug), array(
			'title'   => $judul[$slug].' — ShopEase',
			'judul'   => $judul[$slug],
			'langkah' => $this->katalog_model->langkah_belanja(),
		));
	}

	public function tidak_ditemukan()
	{
		$this->tidak_ada();
	}
}
