<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function login()
	{
		// sudah login? langsung ke dashboard
		if ($this->session->userdata('logged_in'))
		{
			redirect('admin');
		}

		$this->form_validation->set_rules('login', 'Username / email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$user = $this->user_model->attempt(
				$this->input->post('login', TRUE),
				$this->input->post('password')
			);

			if ($user)
			{
				// ganti session id setelah login untuk mencegah session fixation
				$this->session->sess_regenerate(TRUE);
				$this->session->set_userdata(array(
					'logged_in' => TRUE,
					'user_id'   => $user->user_id,
					'username'  => $user->username,
					'full_name' => $user->full_name,
					'role'      => $user->role,
				));
				$this->session->set_flashdata('success', 'Selamat datang, '.$user->full_name.'!');
				redirect('admin');
			}

			$this->session->set_flashdata('danger', 'Username/email atau password salah.');
			redirect('login');
		}

		$this->load->view('auth/login', array('title' => 'Login Admin - Tokobuah'));
	}

	public function logout()
	{
		$this->session->unset_userdata(array('logged_in', 'user_id', 'username', 'full_name', 'role'));
		$this->session->set_flashdata('success', 'Anda sudah logout.');
		redirect('login');
	}
}
