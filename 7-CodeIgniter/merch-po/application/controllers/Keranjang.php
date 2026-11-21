<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keranjang & checkout.
 * Isi keranjang dikelola library Keranjang_belanja (di-autoload dengan nama objek $this->keranjang).
 */
class Keranjang extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('produk_model', 'batch_model', 'pesanan_model'));
	}

	public function index()
	{
		$cart = $this->keranjang->detail();
		$batch = $cart['batch_id'] ? $this->batch_model->find($cart['batch_id']) : NULL;
		$this->render('keranjang/index', array(
			'title' => 'Keranjang',
			'cart'  => $cart,
			'batch' => $batch,
		));
	}

	public function tambah()
	{
		$this->require_post();
		$produk = $this->produk_model->detail((int) $this->input->post('produk_id')) OR show_404();
		$batch  = $this->batch_model->find($produk->batch_id);
		$ukuran = $produk->pakai_ukuran ? $this->input->post('ukuran') : NULL;
		$qty    = (int) $this->input->post('qty');

		if (Batch_model::status($batch) !== 'dibuka')
		{
			$this->flash('danger', 'PO untuk produk ini sedang tidak dibuka.');
		}
		elseif ($produk->pakai_ukuran && ! in_array($ukuran, Produk_model::UKURAN, TRUE))
		{
			$this->flash('danger', 'Pilih ukuran terlebih dahulu.');
		}
		elseif ($qty < 1 || $qty > Keranjang_belanja::MAKS_QTY)
		{
			$this->flash('danger', 'Jumlah harus 1 sampai '.Keranjang_belanja::MAKS_QTY.'.');
		}
		else
		{
			$isi = $this->keranjang->detail();
			if ($isi['batch_id'] && $isi['batch_id'] !== (int) $produk->batch_id)
			{
				$this->flash('warning', 'Keranjang hanya boleh berisi produk dari satu batch PO. Selesaikan atau kosongkan keranjang dulu.');
				redirect('keranjang');
			}
			$this->keranjang->tambah($produk->id, $ukuran, $qty);
			$this->flash('success', '<b>'.html_escape($produk->nama).'</b>'.($ukuran ? ' ukuran '.$ukuran : '').' x'.$qty.' masuk keranjang.');
			redirect('keranjang');
		}
		redirect('produk/'.$produk->id);
	}

	public function ubah()
	{
		$this->require_post();
		foreach ((array) $this->input->post('qty') as $key => $qty)
		{
			$this->keranjang->ubah($key, $qty);
		}
		$this->flash('success', 'Keranjang diperbarui.');
		redirect('keranjang');
	}

	public function hapus()
	{
		$this->require_post();
		$this->keranjang->hapus($this->input->post('key'));
		redirect('keranjang');
	}

	public function checkout()
	{
		$this->require_post();
		if ( ! $this->me)
		{
			// setelah login kembali ke keranjang (bukan ke URL checkout yang khusus POST)
			$this->session->set_userdata('setelah_login', 'keranjang');
			$this->flash('warning', 'Masuk atau daftar dulu untuk menyelesaikan pesanan. Isi keranjang Anda tetap tersimpan.');
			redirect('login');
		}
		$this->require_login('pembeli');

		$cart = $this->keranjang->detail();
		if ( ! $cart['items'])
		{
			redirect('keranjang');
		}

		// 1) batch masih dibuka?
		$batch = $this->batch_model->find($cart['batch_id']);
		if (Batch_model::status($batch) !== 'dibuka')
		{
			$this->flash('danger', 'Maaf, '.html_escape($batch->nama).' sudah ditutup.');
			redirect('keranjang');
		}

		// 2) kuota produk terbatas masih cukup?
		$per_produk = array();
		foreach ($cart['items'] as $it)
		{
			$per_produk[$it->produk->id] = (isset($per_produk[$it->produk->id]) ? $per_produk[$it->produk->id] : 0) + $it->qty;
		}
		foreach ($per_produk as $pid => $qty)
		{
			$p = $this->produk_model->detail($pid);
			$sisa = Produk_model::sisa($p);
			if ($sisa !== NULL && $qty > $sisa)
			{
				$this->flash('danger', 'Kuota <b>'.html_escape($p->nama).'</b> tinggal '.$sisa.'. Kurangi jumlahnya.');
				redirect('keranjang');
			}
		}

		// 3) simpan pesanan
		$catatan = mb_substr(trim(strip_tags((string) $this->input->post('catatan'))), 0, 255);
		$kode = $this->pesanan_model->buat($this->me->id, $batch->id, $cart['items'], $cart['total'], $catatan);
		if ( ! $kode)
		{
			$this->flash('danger', 'Pesanan gagal disimpan, coba lagi.');
			redirect('keranjang');
		}

		$this->keranjang->kosongkan();
		$this->flash('success', 'Pesanan <b>'.$kode.'</b> dibuat. Silakan transfer lalu unggah bukti pembayaran.');
		redirect('pesanan/detail/'.$kode);
	}
}
