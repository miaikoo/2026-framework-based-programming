<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library keranjang belanja buatan sendiri (disimpan di session).
 * Library Cart bawaan CI3 sudah deprecated, jadi dibuat versi sederhana:
 *   session['keranjang'] = array('3|L' => 2, '4|' => 1)  -> "produk_id|ukuran" => qty
 */
class Keranjang_belanja
{
	const KEY = 'keranjang';
	const MAKS_QTY = 10;

	/** @var CI_Controller */
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function raw()
	{
		return (array) $this->CI->session->userdata(self::KEY);
	}

	public function tambah($produk_id, $ukuran, $qty)
	{
		$items = $this->raw();
		$key = (int) $produk_id.'|'.(string) $ukuran;
		$items[$key] = min(self::MAKS_QTY, (isset($items[$key]) ? $items[$key] : 0) + max(1, (int) $qty));
		$this->CI->session->set_userdata(self::KEY, $items);
	}

	public function ubah($key, $qty)
	{
		$items = $this->raw();
		if (isset($items[$key]))
		{
			$qty = (int) $qty;
			if ($qty <= 0) unset($items[$key]);
			else $items[$key] = min(self::MAKS_QTY, $qty);
			$this->CI->session->set_userdata(self::KEY, $items);
		}
	}

	public function hapus($key)
	{
		$this->ubah($key, 0);
	}

	public function kosongkan()
	{
		$this->CI->session->unset_userdata(self::KEY);
	}

	/** jumlah barang (untuk badge di navbar) */
	public function jumlah()
	{
		return array_sum($this->raw());
	}

	/**
	 * Isi keranjang lengkap dengan data produk dari database.
	 * @return array array('items' => [...], 'total' => int, 'batch_id' => int|null)
	 */
	public function detail()
	{
		$this->CI->load->model('produk_model');
		$items = array();
		$total = 0;
		$batch_id = NULL;

		foreach ($this->raw() as $key => $qty)
		{
			list($produk_id, $ukuran) = explode('|', $key);
			$p = $this->CI->produk_model->find((int) $produk_id);
			if ( ! $p)
			{
				$this->hapus($key); // produk sudah dihapus admin
				continue;
			}
			$items[] = (object) array(
				'key'      => $key,
				'produk'   => $p,
				'ukuran'   => $ukuran !== '' ? $ukuran : NULL,
				'qty'      => $qty,
				'subtotal' => $p->harga * $qty,
			);
			$total += $p->harga * $qty;
			$batch_id = $batch_id ?: (int) $p->batch_id;
		}
		return array('items' => $items, 'total' => $total, 'batch_id' => $batch_id);
	}
}
