<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Rekap jumlah produksi per ukuran untuk dikirim ke vendor + daftar pengambilan */
class Rekap extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('pesanan_model', 'batch_model', 'produk_model'));
	}

	public function index($batch_id = NULL)
	{
		$batches = $this->batch_model->all();
		$batch = $batch_id ? $this->batch_model->find((int) $batch_id) : ($batches ? $this->_default_batch($batches) : NULL);
		if ( ! $batch) show_404();

		$this->render('admin/rekap/index', array(
			'title'      => 'Rekap Produksi',
			'batch'      => $batch,
			'batches'    => $batches,
			'rekap'      => $this->pesanan_model->rekap_produksi($batch->id),
			'ambil'      => $this->pesanan_model->daftar_ambil($batch->id),
			'pendapatan' => $this->pesanan_model->pendapatan_sah($batch->id),
		));
	}

	/** Unduh rekap sebagai CSV (bisa dibuka di Excel) */
	public function csv($batch_id = NULL)
	{
		$batch = $this->batch_model->find((int) $batch_id) OR show_404();
		$rekap = $this->pesanan_model->rekap_produksi($batch->id);

		$f = fopen('php://temp', 'w+');
		fwrite($f, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
		fputcsv($f, array_merge(array('Produk'), Produk_model::UKURAN, array('Total')), ';', '"', '');
		foreach ($rekap as $r)
		{
			$kolom = $r->pakai_ukuran ? array_values($r->per_ukuran) : array_fill(0, count(Produk_model::UKURAN), '-');
			fputcsv($f, array_merge(array($r->nama), $kolom, array($r->total)), ';', '"', '');
		}
		rewind($f);
		$csv = stream_get_contents($f);
		fclose($f);

		$this->load->helper('download');
		force_download('rekap-produksi-'.url_title($batch->nama, '-', TRUE).'.csv', $csv);
	}

	/** Default: batch yang sedang dibuka, kalau tidak ada pakai yang terbaru */
	private function _default_batch($batches)
	{
		foreach ($batches as $b)
		{
			if (Batch_model::status($b) === 'dibuka') return $b;
		}
		foreach ($batches as $b)
		{
			if (Batch_model::status($b) === 'ditutup') return $b;
		}
		return $batches[0];
	}
}
