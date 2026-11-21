<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** /produk/<slug> → detail($slug) (lihat config/routes.php) */
class Produk extends MY_Controller
{
	public function detail($slug = NULL)
	{
		$produk = $this->katalog_model->by_slug((string) $slug);
		if ( ! $produk)
		{
			$this->tidak_ada();
		}

		$this->render('produk/detail', array(
			'title'  => $produk->nama.' — ShopEase',
			'p'      => $produk,
			'serupa' => $this->katalog_model->serupa($produk),
		));
	}
}
