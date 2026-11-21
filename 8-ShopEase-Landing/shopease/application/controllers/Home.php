<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Landing page dan penjelajahan katalog.
 *   /                  index()      landing page
 *   /kategori/kereta   kategori()   produk per kategori
 *   /cari?q=...        cari()       hasil pencarian
 *   /langganan         langganan()  (POST) daftar newsletter promo
 */
class Home extends MY_Controller
{
	public function index()
	{
		$this->render('home/landing', array(
			'title'     => 'ShopEase — Toko Miniatur & Koleksi',
			'unggulan'  => $this->katalog_model->by_slug('lokomotif-uap'),
			'pajangan'  => array(
				$this->katalog_model->by_slug('vespa-klasik'),
				$this->katalog_model->by_slug('kapal-pinisi'),
				$this->katalog_model->by_slug('becak-jogja'),
			),
			'terbaru'   => $this->katalog_model->terbaru(4),
			'terlaris'  => $this->katalog_model->terlaris(5),
			'promo'     => $this->katalog_model->promo_aktif(),
			'langkah'   => $this->katalog_model->langkah_belanja(),
			'testimoni' => $this->katalog_model->testimoni(),
			'total_produk' => count($this->katalog_model->semua()),
		));
	}

	public function kategori($slug = NULL)
	{
		$kategori = $this->katalog_model->kategori_by_slug($slug);
		if ( ! $kategori)
		{
			$this->tidak_ada();
		}

		$urut = $this->input->get('urut', TRUE);
		$produk = $this->katalog_model->by_kategori($slug);
		$produk = $this->_urutkan($produk, $urut);

		$this->render('home/daftar', array(
			'title'    => $kategori->nama.' — ShopEase',
			'judul'    => $kategori->nama,
			'subjudul' => $kategori->ket,
			'produk'   => $produk,
			'aktif'    => $slug,
			'urut'     => $urut,
		));
	}

	public function cari()
	{
		$q = trim((string) $this->input->get('q', TRUE));
		$produk = $this->katalog_model->cari($q);

		$this->render('home/daftar', array(
			'title'    => 'Cari "'.$q.'" — ShopEase',
			'judul'    => $q === '' ? 'Cari miniatur' : 'Hasil untuk "'.$q.'"',
			'subjudul' => $q === '' ? 'Ketik nama miniatur, skala, atau bahan.' : count($produk).' miniatur ditemukan.',
			'produk'   => $produk,
			'aktif'    => NULL,
			'urut'     => NULL,
			'q'        => $q,
		));
	}

	public function langganan()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('');
		}

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');
		if ($this->form_validation->run())
		{
			// Belum ada database: email cukup diingat di session agar form berubah jadi pesan terima kasih.
			$this->session->set_userdata('pelanggan_newsletter', $this->input->post('email', TRUE));
			$this->session->set_flashdata('newsletter', array('ok' => TRUE, 'pesan' => 'Terdaftar. Info diskon berikutnya dikirim ke '.$this->input->post('email', TRUE).'.'));
		}
		else
		{
			$this->session->set_flashdata('newsletter', array('ok' => FALSE, 'pesan' => strip_tags(form_error('email'))));
		}
		redirect('#newsletter');
	}

	/** urut: murah | mahal | terlaris | (default) terbaru */
	private function _urutkan(array $produk, $urut)
	{
		$cmp = array(
			'murah'    => function ($a, $b) { return $a->harga_akhir - $b->harga_akhir; },
			'mahal'    => function ($a, $b) { return $b->harga_akhir - $a->harga_akhir; },
			'terlaris' => function ($a, $b) { return $b->terjual - $a->terjual; },
		);
		usort($produk, isset($cmp[$urut]) ? $cmp[$urut] : function ($a, $b) { return strcmp($b->masuk, $a->masuk); });
		return $produk;
	}
}
