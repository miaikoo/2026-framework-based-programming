<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller dasar aplikasi.
 * render() membungkus view halaman ke dalam layout AdminLTE (views/layouts/app.php),
 * jadi view halaman cukup berisi konten utamanya saja.
 */
class MY_Controller extends CI_Controller
{
	protected function render($view, array $data = array())
	{
		$data['content'] = $this->load->view($view, $data, TRUE);
		$this->load->view('layouts/app', $data);
	}

	protected function flash($type, $message)
	{
		$this->session->set_flashdata('flash', array('type' => $type, 'message' => $message));
	}
}

/**
 * Controller yang wajib login. Turunan class ini bisa membatasi role dengan
 * properti $role, mis. protected $role = 'admin';
 */
class Auth_Controller extends MY_Controller
{
	/** @var string|null role yang boleh mengakses controller ini (NULL = semua role) */
	protected $role = NULL;

	/** @var object akun yang sedang login */
	protected $me;

	public function __construct()
	{
		parent::__construct();

		$id = $this->session->userdata('user_id');
		$this->load->model('user_model');
		$this->me = $id ? $this->user_model->find($id) : NULL;

		if ( ! $this->me)
		{
			$this->flash('warning', 'Sesi berakhir, silakan login kembali.');
			redirect('login');
		}
		if ($this->role !== NULL && $this->me->role !== $this->role)
		{
			show_error('Halaman ini khusus untuk '.$this->role.'.', 403, 'Akses ditolak');
		}

		$this->load->vars(array('me' => $this->me));
	}

	/** Pastikan request memakai POST (untuk aksi yang mengubah data) */
	protected function require_post()
	{
		if ($this->input->method() !== 'post')
		{
			show_error('Metode tidak diizinkan.', 405);
		}
	}
}

/**
 * Dasar controller master data milik admin.
 * Menyediakan callback validasi "unik kecuali data yang sedang diedit".
 */
class Admin_Controller extends Auth_Controller
{
	protected $role = 'admin';

	/** model yang dipakai callback _unique_except */
	protected $crud_model;

	/** id data yang sedang diedit */
	protected $edit_id;

	public function _unique_except($value, $field)
	{
		if ($this->crud_model->exists_except($field, $value, $this->edit_id))
		{
			$this->form_validation->set_message('_unique_except', '{field} sudah dipakai data lain.');
			return FALSE;
		}
		return TRUE;
	}
}

class Dosen_Controller extends Auth_Controller
{
	protected $role = 'dosen';
}

class Mahasiswa_Controller extends Auth_Controller
{
	protected $role = 'mahasiswa';
}
