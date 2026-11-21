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
