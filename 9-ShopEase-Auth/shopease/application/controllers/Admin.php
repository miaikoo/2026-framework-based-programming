<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Panel admin (dijaga hook: 'admin' => array('admin')).
 *   /admin                     ringkasan pengguna & aktivitas login
 *   /admin/pengguna            kelola pengguna (cari, filter peran)
 *   /admin/peran/{id}          (POST) ubah peran
 *   /admin/hapus/{id}          (POST) hapus pengguna
 */
class Admin extends MY_Controller
{
	public function index()
	{
		$this->render('admin/index', array(
			'title'      => 'Dashboard admin — ShopEase',
			'per_peran'  => $this->user_model->hitung_per_peran(),
			'baru_7hari' => $this->user_model->daftar_sejak(date('Y-m-d H:i:s', strtotime('-7 days'))),
			'login24'    => $this->login_attempt_model->ringkasan_24jam(),
			'terbaru'    => $this->user_model->daftar_pengguna(NULL, NULL, 5),
			'aktivitas'  => $this->login_attempt_model->terbaru(8),
		));
	}

	public function pengguna()
	{
		$peran = $this->input->get('peran', TRUE);
		$q = trim((string) $this->input->get('q', TRUE));
		$this->render('admin/pengguna', array(
			'title'     => 'Kelola pengguna — ShopEase',
			'daftar'    => $this->user_model->daftar_pengguna($peran, $q),
			'per_peran' => $this->user_model->hitung_per_peran(),
			'peran'     => $peran,
			'q'         => $q,
		));
	}

	public function peran($id = NULL)
	{
		$this->_wajib_post();
		$u = $this->user_model->cari($id);
		$baru = $this->input->post('role');

		if ( ! $u || ! in_array($baru, User_model::PERAN, TRUE))
		{
			$this->session->set_flashdata('galat', 'Data peran tidak valid.');
		}
		elseif ((int) $u->id_user === (int) $this->auth->user()->id_user)
		{
			// mencegah admin terakhir mengunci dirinya sendiri dari panel admin
			$this->session->set_flashdata('galat', 'Kamu tidak bisa mengubah peran akunmu sendiri.');
		}
		else
		{
			$this->user_model->ubah_peran($u->id_user, $baru);
			$this->session->set_flashdata('sukses', 'Peran '.$u->name.' diubah menjadi '.$baru.'. Berlaku di request berikutnya tanpa perlu login ulang.');
		}
		redirect('admin/pengguna'.$this->_kembali_query());
	}

	public function hapus($id = NULL)
	{
		$this->_wajib_post();
		$u = $this->user_model->cari($id);
		if ( ! $u)
		{
			$this->session->set_flashdata('galat', 'Pengguna tidak ditemukan.');
		}
		elseif ((int) $u->id_user === (int) $this->auth->user()->id_user)
		{
			$this->session->set_flashdata('galat', 'Kamu tidak bisa menghapus akunmu sendiri.');
		}
		else
		{
			$this->user_model->hapus($u->id_user);   // token Ingat saya ikut terhapus (ON DELETE CASCADE)
			$this->session->set_flashdata('sukses', 'Akun '.$u->name.' dihapus.');
		}
		redirect('admin/pengguna'.$this->_kembali_query());
	}

	private function _kembali_query()
	{
		$q = (string) $this->input->post('kembali');
		return $q !== '' && $q[0] === '?' ? $q : '';
	}

	private function _wajib_post()
	{
		if ($this->input->method() !== 'post') show_error('Metode tidak diizinkan.', 405);
	}
}
