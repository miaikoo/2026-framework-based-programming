<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keranjang belanja sederhana di session: array(slug => jumlah).
 *   GET  /keranjang          lihat isi
 *   POST /keranjang/tambah   tambah produk
 *   POST /keranjang/hapus    hapus satu produk
 */
class Keranjang extends MY_Controller
{
	const MAKS = 10;

	public function index()
	{
		$isi = array();
		$total = 0;
		foreach ($this->_isi() as $slug => $qty)
		{
			$p = $this->katalog_model->by_slug($slug);
			if ( ! $p) continue;
			$isi[] = (object) array('p' => $p, 'qty' => $qty, 'subtotal' => $p->harga_akhir * $qty);
			$total += $p->harga_akhir * $qty;
		}

		$this->render('keranjang/index', array(
			'title' => 'Keranjang — ShopEase',
			'isi'   => $isi,
			'total' => $total,
			'hemat' => array_sum(array_map(function ($i) { return ($i->p->harga - $i->p->harga_akhir) * $i->qty; }, $isi)),
		));
	}

	public function tambah()
	{
		$this->_wajib_post();
		$slug = (string) $this->input->post('slug', TRUE);
		$produk = $this->katalog_model->by_slug($slug);
		if ( ! $produk)
		{
			$this->tidak_ada();
		}

		$isi = $this->_isi();
		$baru = min(self::MAKS, $produk->stok, (isset($isi[$slug]) ? $isi[$slug] : 0) + max(1, (int) $this->input->post('qty')));
		$isi[$slug] = $baru;
		$this->session->set_userdata('keranjang', $isi);
		$this->session->set_flashdata('notif', $produk->nama.' masuk keranjang ('.$baru.' pcs).');

		// kartu produk mengirim field "kembali" (URI halaman asal; string kosong = beranda).
		// Form detail produk tidak mengirimnya, sehingga diarahkan ke keranjang.
		$kembali = $this->input->post('kembali');
		redirect($kembali !== NULL && strpos($kembali, '//') === FALSE ? $kembali : 'keranjang');
	}

	public function hapus()
	{
		$this->_wajib_post();
		$isi = $this->_isi();
		unset($isi[(string) $this->input->post('slug')]);
		$this->session->set_userdata('keranjang', $isi);
		redirect('keranjang');
	}

	private function _isi()
	{
		return (array) $this->session->userdata('keranjang');
	}

	private function _wajib_post()
	{
		if ($this->input->method() !== 'post')
		{
			show_error('Metode tidak diizinkan.', 405);
		}
	}
}
