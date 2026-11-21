<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
	public function login()
	{
		if ($this->me) redirect($this->me->role === 'admin' ? 'admin' : 'toko');

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run())
		{
			$user = $this->user_model->attempt($this->input->post('email'), $this->input->post('password'));
			if ($user)
			{
				$tujuan = $this->session->userdata('setelah_login');
				$this->session->sess_regenerate(TRUE);
				$this->session->set_userdata('user_id', $user->id);
				$this->session->unset_userdata('setelah_login');
				$this->flash('success', 'Halo, '.html_escape($user->nama).'!');
				redirect($user->role === 'admin' ? 'admin' : ($tujuan ?: 'toko'));
			}
			$this->flash('danger', 'Email atau password salah.');
			redirect('login');
		}
		$this->render('auth/login', array('title' => 'Masuk'));
	}

	public function daftar()
	{
		if ($this->me) redirect('toko');

		$this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('no_wa', 'Nomor WhatsApp', 'trim|required|regex_match[/^08[0-9]{8,12}$/]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
		$this->form_validation->set_rules('konfirmasi', 'Konfirmasi password', 'required|matches[password]');
		$this->form_validation->set_message('regex_match', '{field} harus diawali 08 dan berisi 10-14 angka.');

		if ($this->form_validation->run())
		{
			$id = $this->user_model->daftar(
				$this->input->post('nama', TRUE), $this->input->post('email', TRUE),
				$this->input->post('no_wa', TRUE), $this->input->post('password'));
			$this->session->sess_regenerate(TRUE);
			$this->session->set_userdata('user_id', $id);
			$this->flash('success', 'Akun berhasil dibuat. Selamat berbelanja!');
			redirect($this->session->userdata('setelah_login') ?: 'toko');
		}
		$this->render('auth/daftar', array('title' => 'Daftar Akun'));
	}

	public function logout()
	{
		$this->session->unset_userdata(array('user_id', 'keranjang'));
		$this->flash('success', 'Anda sudah keluar.');
		redirect('toko');
	}
}
