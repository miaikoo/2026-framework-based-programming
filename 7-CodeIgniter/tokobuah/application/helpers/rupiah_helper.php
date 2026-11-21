<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('rupiah'))
{
	/**
	 * Format angka menjadi rupiah, contoh: 32000 -> "Rp 32.000"
	 */
	function rupiah($angka)
	{
		return 'Rp '.number_format((float) $angka, 0, ',', '.');
	}
}
