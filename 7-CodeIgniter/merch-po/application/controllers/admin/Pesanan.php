<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kelola pesanan: verifikasi pembayaran dan update status pengambilan */
class Pesanan extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('pesanan_model', 'batch_model'));
	}

	public function index()
	{
		$status = $this->input->get('status', TRUE);
		$batch  = $this->input->get('batch', TRUE);
		$this->render('admin/pesanan/index', array(
			'title'      => 'Pesanan',
			'pesanan'    => $this->pesanan_model->daftar_admin($status, $batch),
			'per_status' => $this->pesanan_model->hitung_per_status(),
			'opsi_batch' => $this->batch_model->dropdown('nama'),
			'f_status'   => $status,
			'f_batch'    => $batch,
		));
	}

	public function detail($kode = NULL)
	{
		$o = $this->pesanan_model->by_kode((string) $kode) OR show_404();
		$this->render('admin/pesanan/detail', array(
			'title' => 'Pesanan '.$o->kode,
			'o'     => $o,
			'items' => $this->pesanan_model->items($o->id),
		));
	}

	/** POST aksi=verifikasi|tolak|siap|selesai */
	public function ubah_status($kode = NULL)
	{
		$this->require_post();
		$o = $this->pesanan_model->by_kode((string) $kode) OR show_404();
		$aksi = $this->input->post('aksi');

		if ( ! isset(Pesanan_model::TRANSISI[$aksi]) || Pesanan_model::TRANSISI[$aksi][0] !== $o->status)
		{
			$this->flash('danger', 'Aksi tidak valid untuk status pesanan saat ini.');
			redirect('admin/pesanan/detail/'.$o->kode);
		}

		$data = array('status' => Pesanan_model::TRANSISI[$aksi][1]);
		if ($aksi === 'verifikasi')
		{
			$data['diverifikasi_at'] = date('Y-m-d H:i:s');
		}
		if ($aksi === 'tolak')
		{
			$alasan = trim(strip_tags((string) $this->input->post('alasan_tolak')));
			if ($alasan === '')
			{
				$this->flash('danger', 'Isi alasan penolakan agar pembeli tahu apa yang harus diperbaiki.');
				redirect('admin/pesanan/detail/'.$o->kode);
			}
			$data['alasan_tolak'] = mb_substr($alasan, 0, 255);
		}

		$this->pesanan_model->update($o->id, $data);
		$this->flash('success', 'Status pesanan <b>'.$o->kode.'</b> menjadi <b>'.Pesanan_model::STATUS[$data['status']][0].'</b>.');
		redirect('admin/pesanan/detail/'.$o->kode);
	}

	public function bukti($kode = NULL)
	{
		$o = $this->pesanan_model->by_kode((string) $kode) OR show_404();
		kirim_bukti('./storage/bukti/', $o->bukti_bayar);
	}
}
