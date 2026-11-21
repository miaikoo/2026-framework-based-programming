<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('rupiah'))
{
	function rupiah($angka)
	{
		return 'Rp'.number_format((float) $angka, 0, ',', '.');
	}
}

if ( ! function_exists('tanggal_id'))
{
	function tanggal_id($datetime)
	{
		$bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		$t = strtotime($datetime);
		return date('j', $t).' '.$bulan[(int) date('n', $t)].' '.date('Y', $t);
	}
}

if ( ! function_exists('badge_peran'))
{
	/** Label peran pengguna */
	function badge_peran($peran)
	{
		$nama = array('customer' => 'Customer', 'operator' => 'Operator', 'admin' => 'Admin');
		return '<span class="peran peran-'.$peran.'">'.(isset($nama[$peran]) ? $nama[$peran] : html_escape($peran)).'</span>';
	}
}

if ( ! function_exists('waktu_lalu'))
{
	/** "2026-11-21 09:00:00" -> "3 jam lalu" */
	function waktu_lalu($datetime)
	{
		if ( ! $datetime) return 'belum pernah';
		$d = time() - strtotime($datetime);
		if ($d < 60) return 'baru saja';
		if ($d < 3600) return floor($d / 60).' menit lalu';
		if ($d < 86400) return floor($d / 3600).' jam lalu';
		if ($d < 86400 * 30) return floor($d / 86400).' hari lalu';
		return tanggal_id($datetime);
	}
}
