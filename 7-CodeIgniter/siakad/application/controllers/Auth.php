<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
	public function login()
	{
		if ($this->session->userdata('user_id'))
		{
			redirect('dashboard');
		}

		$this->load->model('user_model');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run())
		{
			$user = $this->user_model->attempt($this->input->post('username', TRUE), $this->input->post('password'));
			if ($user)
			{
				$this->session->sess_regenerate(TRUE);
				$this->session->set_userdata('user_id', $user->id);
				redirect('dashboard');
			}
			$this->flash('danger', 'Username atau password salah.');
			redirect('login');
		}

		$this->load->view('auth/login');
	}

	public function logout()
	{
		$this->session->unset_userdata('user_id');
		$this->flash('success', 'Anda telah keluar dari SIAKAD.');
		redirect('login');
	}
}
