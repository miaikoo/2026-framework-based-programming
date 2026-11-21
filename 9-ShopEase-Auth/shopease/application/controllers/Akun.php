<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman akun untuk semua peran yang sudah login (dijaga hook: 'akun' => 'login').
 *   /akun                 profil + peta akses
 *   /akun/simpan          (POST) ubah nama, HP, alamat
 *   /akun/password        (POST) ganti password
 *   /akun/lupakan         (POST) cabut semua perangkat "Ingat saya"
 */
class Akun extends MY_Controller
{
	public function index()
	{
		$this->config->load('akses');
		$this->render('akun/index', array(
			'title'     => 'Akun saya — ShopEase',
			'u'         => $this->auth->user(),
			'perangkat' => $this->remember_token_model->jumlah_aktif($this->auth->user()->id_user),
			'diingat'   => $this->auth->diingat(),
			'aturan'    => $this->config->item('akses'),
		));
	}

	public function simpan()
	{
		$this->_wajib_post();
		$this->form_validation->set_rules('name', 'Nama', 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('phone', 'Nomor HP', array('trim', 'regex_match[/^(\+62|62|0)8[0-9]{7,11}$/]'),
			array('regex_match' => 'Nomor HP harus diawali 08 atau +628, 10–14 digit.'));
		$this->form_validation->set_rules('address', 'Alamat', 'trim|max_length[300]');

		if ( ! $this->form_validation->run())
		{
			return $this->index();
		}

		$phone = (string) $this->input->post('phone', TRUE);
		$address = (string) $this->input->post('address', TRUE);
		$this->user_model->ubah_profil($this->auth->user()->id_user, array(
			'name'    => $this->input->post('name', TRUE),
			'phone'   => $phone !== '' ? $phone : NULL,
			'address' => $address !== '' ? $address : NULL,
		));
		$this->session->set_flashdata('sukses', 'Profil disimpan.');
		redirect('akun');
	}

	public function password()
	{
		$this->_wajib_post();
		$this->form_validation->set_rules('password_lama', 'Password lama', 'required|callback__cek_lama');
		$this->form_validation->set_rules('password_baru', 'Password baru', 'required|min_length[8]|max_length[72]|callback__beda_dan_kuat');
		$this->form_validation->set_rules('password_ulang', 'Ulangi password baru', 'required|matches[password_baru]',
			array('matches' => 'Ulangi password baru tidak sama.'));

		if ( ! $this->form_validation->run())
		{
			return $this->index();
		}

		$id = $this->auth->user()->id_user;
		$this->user_model->ganti_password($id, $this->input->post('password_baru'));
		// perangkat lain yang diingat ikut dicabut setelah password diganti
		$dicabut = $this->auth->lupakan_semua_perangkat($id);
		$this->session->sess_regenerate(TRUE);
		$this->session->set_flashdata('sukses', 'Password diganti.'.($dicabut ? ' '.$dicabut.' perangkat yang diingat sudah dikeluarkan.' : ''));
		redirect('akun');
	}

	public function lupakan()
	{
		$this->_wajib_post();
		$n = $this->auth->lupakan_semua_perangkat($this->auth->user()->id_user);
		$this->session->set_flashdata('sukses', $n ? $n.' perangkat tidak lagi diingat.' : 'Tidak ada perangkat yang diingat.');
		redirect('akun');
	}

	public function _cek_lama($pw)
	{
		$u = $this->user_model->cari($this->auth->user()->id_user);
		if ( ! password_verify((string) $pw, $u->password))
		{
			$this->form_validation->set_message('_cek_lama', 'Password lama salah.');
			return FALSE;
		}
		return TRUE;
	}

	public function _beda_dan_kuat($pw)
	{
		if ($pw === $this->input->post('password_lama'))
		{
			$this->form_validation->set_message('_beda_dan_kuat', 'Password baru harus berbeda dari password lama.');
			return FALSE;
		}
		if ( ! preg_match('/[A-Za-z]/', $pw) || ! preg_match('/[0-9]/', $pw))
		{
			$this->form_validation->set_message('_beda_dan_kuat', 'Password harus berisi huruf dan angka.');
			return FALSE;
		}
		return TRUE;
	}

	private function _wajib_post()
	{
		if ($this->input->method() !== 'post') redirect('akun');
	}
}
