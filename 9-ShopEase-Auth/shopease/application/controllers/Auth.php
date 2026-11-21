<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Registrasi, login, dan logout.
 *   /register  GET form, POST simpan akun baru (peran selalu customer)
 *   /login     GET form, POST periksa email & password
 *   /logout    POST saja (memakai token CSRF)
 * Pengguna yang sudah login tidak bisa membuka /login dan /register (diatur hook Penjaga_akses).
 */
class Auth extends MY_Controller
{
	public function register()
	{
		if ($this->input->method() === 'post')
		{
			$this->form_validation->set_rules('name', 'Nama', 'trim|required|min_length[3]|max_length[100]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]|is_unique[users.email]',
				array('is_unique' => 'Email ini sudah terdaftar. Silakan masuk.'));
			// aturan berbentuk array karena regex mengandung "|" (string aturan CI3 dipecah dengan "|")
			$this->form_validation->set_rules('phone', 'Nomor HP', array('trim', 'regex_match[/^(\+62|62|0)8[0-9]{7,11}$/]'),
				array('regex_match' => 'Nomor HP harus diawali 08 atau +628, 10–14 digit.'));
			$this->form_validation->set_rules('address', 'Alamat', 'trim|max_length[300]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[72]|callback__password_kuat');
			$this->form_validation->set_rules('password_confirm', 'Ulangi password', 'required|matches[password]',
				array('matches' => 'Ulangi password tidak sama.'));

			if ($this->form_validation->run())
			{
				$id = $this->user_model->daftar(array(
					'name'     => $this->input->post('name', TRUE),
					'email'    => $this->input->post('email', TRUE),
					'phone'    => (string) $this->input->post('phone', TRUE),
					'address'  => (string) $this->input->post('address', TRUE),
					'password' => $this->input->post('password'),
				));
				// akun baru langsung masuk, tidak perlu login ulang
				$this->auth->masuk($this->user_model->cari($id));
				$this->session->set_flashdata('sukses', 'Akun berhasil dibuat. Selamat datang di ShopEase!');
				redirect('akun');
			}
		}

		$this->render('auth/register', array('title' => 'Daftar — ShopEase'));
	}

	public function login()
	{
		$email = '';
		$galat = NULL;

		if ($this->input->method() === 'post')
		{
			$email    = strtolower(trim((string) $this->input->post('email', TRUE)));
			$password = (string) $this->input->post('password');

			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run())
			{
				$kunci = $this->auth->terkunci_sampai($email);
				if ($kunci)
				{
					// percobaan saat terkunci tidak dicatat agar tidak memperpanjang kuncian
					$galat = 'Terlalu banyak percobaan gagal untuk email ini. Coba lagi pukul '.date('H:i', $kunci).' ('.max(1, ceil(($kunci - time()) / 60)).' menit lagi).';
				}
				elseif ($user = $this->auth->verifikasi($email, $password))
				{
					$this->auth->catat_percobaan($email, TRUE);
					$this->auth->masuk($user, (bool) $this->input->post('ingat'));

					$tujuan = $this->session->userdata('tujuan_login');
					$this->session->unset_userdata('tujuan_login');
					$this->session->set_flashdata('sukses', 'Halo, '.$user->name.'. Kamu berhasil masuk.');
					redirect($tujuan ?: $this->auth->beranda_peran($user->role));
				}
				else
				{
					$this->auth->catat_percobaan($email, FALSE);
					$sisa = $this->auth->sisa_percobaan($email);
					// pesan sama untuk email tak terdaftar & password salah
					$galat = 'Email atau password salah.'.($sisa > 0 && $sisa <= 3 ? ' Sisa '.$sisa.' percobaan sebelum dikunci sementara.' : '');
					if ($sisa === 0) $galat = 'Email atau password salah. Login untuk email ini dikunci '.$this->config->item('login_kunci_menit').' menit.';
				}
			}
		}

		$this->render('auth/login', array(
			'title' => 'Masuk — ShopEase',
			'email' => $email ?: (string) $this->input->get('email', TRUE),
			'galat' => $galat,
		));
	}

	public function logout()
	{
		if ($this->input->method() !== 'post')
		{
			show_error('Logout harus lewat tombol Keluar.', 405);
		}
		$this->auth->keluar();
		$this->session->set_flashdata('sukses', 'Kamu sudah keluar. Sampai jumpa lagi.');
		redirect('login');
	}

	/** Password wajib mengandung huruf dan angka */
	public function _password_kuat($pw)
	{
		if ( ! preg_match('/[A-Za-z]/', $pw) || ! preg_match('/[0-9]/', $pw))
		{
			$this->form_validation->set_message('_password_kuat', 'Password harus berisi huruf dan angka.');
			return FALSE;
		}
		return TRUE;
	}
}
