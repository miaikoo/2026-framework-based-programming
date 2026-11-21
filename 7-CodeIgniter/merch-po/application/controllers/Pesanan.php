<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Pesanan milik pembeli yang login */
class Pesanan extends MY_Controller
{
	const BUKTI_DIR = './storage/bukti/';

	public function __construct()
	{
		parent::__construct();
		$this->require_login();
		$this->load->model('pesanan_model');
	}

	public function index()
	{
		$this->render('pesanan/index', array(
			'title'   => 'Pesanan Saya',
			'pesanan' => $this->pesanan_model->by_user($this->me->id),
		));
	}

	public function detail($kode = NULL)
	{
		$o = $this->_milik_saya($kode);
		$this->render('pesanan/detail', array(
			'title'   => 'Pesanan '.$o->kode,
			'o'       => $o,
			'items'   => $this->pesanan_model->items($o->id),
		));
	}

	/** Upload bukti transfer (boleh ulang bila sebelumnya ditolak) */
	public function upload($kode = NULL)
	{
		$this->require_post();
		$o = $this->_milik_saya($kode);

		if ( ! in_array($o->status, array('menunggu_pembayaran', 'ditolak'), TRUE))
		{
			$this->flash('warning', 'Bukti pembayaran untuk pesanan ini sudah tidak bisa diubah.');
			redirect('pesanan/detail/'.$o->kode);
		}

		$this->load->library('upload', array(
			'upload_path'   => self::BUKTI_DIR,
			'allowed_types' => 'jpg|jpeg|png|pdf',
			'max_size'      => 2048,
			'encrypt_name'  => TRUE,   // nama acak agar tidak bisa ditebak
		));

		if ( ! $this->upload->do_upload('bukti'))
		{
			$this->flash('danger', 'Upload gagal: '.$this->upload->display_errors('', ''));
			redirect('pesanan/detail/'.$o->kode);
		}

		if ($o->bukti_bayar && is_file(self::BUKTI_DIR.$o->bukti_bayar))
		{
			unlink(self::BUKTI_DIR.$o->bukti_bayar);
		}
		$this->pesanan_model->update($o->id, array(
			'bukti_bayar'  => $this->upload->data('file_name'),
			'status'       => 'menunggu_verifikasi',
			'dibayar_at'   => date('Y-m-d H:i:s'),
			'alasan_tolak' => NULL,
		));
		$this->flash('success', 'Bukti pembayaran terkirim. Admin akan memverifikasi dalam 1x24 jam.');
		redirect('pesanan/detail/'.$o->kode);
	}

	public function batal($kode = NULL)
	{
		$this->require_post();
		$o = $this->_milik_saya($kode);
		if ($o->status === 'menunggu_pembayaran')
		{
			$this->pesanan_model->update($o->id, array('status' => 'dibatalkan'));
			$this->flash('success', 'Pesanan '.$o->kode.' dibatalkan.');
		}
		redirect('pesanan/detail/'.$o->kode);
	}

	/** Tampilkan file bukti (folder storage/ tidak bisa diakses langsung) */
	public function bukti($kode = NULL)
	{
		$o = $this->_milik_saya($kode);
		kirim_bukti(self::BUKTI_DIR, $o->bukti_bayar);
	}

	/** Pembeli hanya boleh membuka pesanannya sendiri */
	private function _milik_saya($kode)
	{
		$o = $this->pesanan_model->by_kode((string) $kode);
		if ( ! $o || (int) $o->user_id !== (int) $this->me->id)
		{
			show_404();
		}
		return $o;
	}
}
