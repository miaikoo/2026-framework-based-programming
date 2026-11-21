<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hook penjaga akses (pengganti Filter CI4 di CodeIgniter 3).
 * Dijalankan pada titik post_controller_constructor, yaitu setelah controller
 * dibuat dan sebelum method-nya dipanggil, berdasarkan peta config/akses.php.
 */
class Penjaga_akses
{
	public function periksa()
	{
		$CI =& get_instance();
		$segmen = (string) $CI->uri->segment(1);

		// 1) halaman khusus tamu: yang sudah login dialihkan ke beranda perannya
		if (in_array($segmen, $CI->config->item('khusus_tamu'), TRUE) && $CI->auth->cek())
		{
			redirect($CI->auth->beranda_peran());
		}

		$aturan = $CI->config->item('akses');
		if ( ! isset($aturan[$segmen]))
		{
			return; // halaman publik
		}

		// 2) wajib login
		if ( ! $CI->auth->cek())
		{
			$tujuan = uri_string().($CI->input->server('QUERY_STRING') ? '?'.$CI->input->server('QUERY_STRING') : '');
			$CI->session->set_userdata('tujuan_login', $tujuan);
			$CI->session->set_flashdata('info', 'Masuk dulu untuk membuka halaman itu.');
			redirect('login');
		}

		// 3) wajib peran tertentu
		$izin = $aturan[$segmen];
		if (is_array($izin) && ! in_array($CI->auth->peran(), $izin, TRUE))
		{
			$this->_tolak($CI, $izin);
		}
	}

	/** Tampilkan halaman 403 lalu hentikan request */
	private function _tolak($CI, array $izin)
	{
		$CI->output->set_status_header(403);
		$data = array(
			'title'   => 'Akses ditolak — ShopEase',
			'izin'    => $izin,
			'halaman' => uri_string(),
		);
		$data['content'] = $CI->load->view('halaman/ditolak', $data, TRUE);
		$CI->load->view('layouts/main', $data);
		$CI->output->_display();
		exit;
	}
}
