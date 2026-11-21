<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('rupiah'))
{
	function rupiah($angka)
	{
		return 'Rp '.number_format((float) $angka, 0, ',', '.');
	}
}

if ( ! function_exists('tgl'))
{
	/** "2026-11-30 23:59:59" -> "30 Nov 2026" (opsional dengan jam) */
	function tgl($datetime, $jam = FALSE)
	{
		if ( ! $datetime) return '-';
		$bulan = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
		$t = strtotime($datetime);
		return date('j', $t).' '.$bulan[(int) date('n', $t)].' '.date('Y', $t).($jam ? ', '.date('H:i', $t) : '');
	}
}

if ( ! function_exists('badge_status'))
{
	/** Badge status pesanan (definisi status ada di Pesanan_model::STATUS) */
	function badge_status($status)
	{
		$s = Pesanan_model::STATUS[$status];
		return '<span class="badge badge-'.$s[1].'"><i class="fas '.$s[2].' mr-1"></i>'.$s[0].'</span>';
	}
}

if ( ! function_exists('badge_batch'))
{
	function badge_batch($status)
	{
		$map = array('segera' => array('info', 'Segera dibuka'), 'dibuka' => array('success', 'PO dibuka'), 'ditutup' => array('secondary', 'PO ditutup'));
		return '<span class="badge badge-'.$map[$status][0].'">'.$map[$status][1].'</span>';
	}
}

if ( ! function_exists('kirim_bukti'))
{
	/** Kirim file bukti pembayaran ke browser (dipakai controller pembeli & admin) */
	function kirim_bukti($folder, $nama_file)
	{
		$path = $folder.basename((string) $nama_file);
		if ( ! $nama_file || ! is_file($path))
		{
			show_404();
		}
		get_instance()->load->helper('file');
		header('Content-Type: '.get_mime_by_extension($path));
		header('Content-Length: '.filesize($path));
		header('Content-Disposition: inline; filename="'.basename($path).'"');
		header('X-Content-Type-Options: nosniff');
		readfile($path);
		exit;
	}
}

if ( ! function_exists('invalid'))
{
	function invalid($field)
	{
		return form_error($field) ? ' is-invalid' : '';
	}

	function pesan_error($field)
	{
		return form_error($field, '<div class="invalid-feedback">', '</div>');
	}

	function nilai($field, $row, $default = '')
	{
		return set_value($field, $row ? $row->$field : $default);
	}
}
