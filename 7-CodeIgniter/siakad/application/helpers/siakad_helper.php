<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('tanggal_indo'))
{
	/** "2026-11-20" -> "20 November 2026" (atau "Jumat, 20 November 2026") */
	function tanggal_indo($tanggal, $dengan_hari = FALSE)
	{
		$bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		$hari  = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
		$t = strtotime($tanggal);
		$hasil = date('j', $t).' '.$bulan[(int) date('n', $t)].' '.date('Y', $t);
		return $dengan_hari ? $hari[(int) date('w', $t)].', '.$hasil : $hasil;
	}
}

if ( ! function_exists('jam'))
{
	/** "07:30:00" -> "07:30" */
	function jam($time)
	{
		return $time ? substr($time, 0, 5) : '-';
	}
}

if ( ! function_exists('badge_presensi'))
{
	/** Badge berwarna untuk status H/I/S/A */
	function badge_presensi($status, $singkat = FALSE)
	{
		$map = array(
			'H' => array('success', 'Hadir'),
			'I' => array('info', 'Izin'),
			'S' => array('warning', 'Sakit'),
			'A' => array('danger', 'Alpa'),
		);
		if ( ! isset($map[$status]))
		{
			return '<span class="badge badge-light">'.($singkat ? '–' : 'Belum').'</span>';
		}
		return '<span class="badge badge-'.$map[$status][0].'">'.($singkat ? $status : $map[$status][1]).'</span>';
	}
}

if ( ! function_exists('invalid'))
{
	/** class "is-invalid" bila field gagal validasi + pesan errornya */
	function invalid($field)
	{
		return form_error($field) ? ' is-invalid' : '';
	}

	function pesan_error($field)
	{
		return form_error($field, '<div class="invalid-feedback">', '</div>');
	}

	/** nilai input: dari POST (saat validasi gagal) atau dari data yang diedit */
	function nilai($field, $row, $default = '')
	{
		return set_value($field, $row ? $row->$field : $default);
	}
}

if ( ! function_exists('warna_persen'))
{
	/** Warna progress bar berdasarkan persentase kehadiran */
	function warna_persen($persen)
	{
		if ($persen === NULL) return 'secondary';
		if ($persen >= 85) return 'success';
		if ($persen >= 75) return 'warning';
		return 'danger';
	}
}
