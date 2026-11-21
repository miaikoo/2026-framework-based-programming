<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Ganti password (semua role). */
class Profil extends Auth_Controller
{
	public function index()
	{
		$this->form_validation->set_rules('password_lama', 'Password lama', 'required|callback__cek_password_lama');
		$this->form_validation->set_rules('password_baru', 'Password baru', 'required|min_length[8]|differs[password_lama]');
		$this->form_validation->set_rules('konfirmasi', 'Konfirmasi password', 'required|matches[password_baru]');

		if ($this->form_validation->run())
		{
			$this->user_model->change_password($this->me->id, $this->input->post('password_baru'));
			$this->flash('success', 'Password berhasil diganti.');
			redirect('profil');
		}

		$this->render('profil/index', array('title' => 'Profil & Password'));
	}

	public function _cek_password_lama($password)
	{
		if ( ! password_verify((string) $password, $this->me->password))
		{
			$this->form_validation->set_message('_cek_password_lama', '{field} tidak sesuai.');
			return FALSE;
		}
		return TRUE;
	}
}
