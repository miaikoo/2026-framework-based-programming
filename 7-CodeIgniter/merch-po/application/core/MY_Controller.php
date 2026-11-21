<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller dasar.
 * - $this->me berisi akun yang login (atau NULL untuk pengunjung)
 * - render() membungkus view ke layout toko atau admin
 */
class MY_Controller extends CI_Controller
{
	protected $me;

	/** layout yang dipakai render(): 'toko' atau 'admin' */
	protected $layout = 'toko';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$id = $this->session->userdata('user_id');
		$this->me = $id ? $this->user_model->find($id) : NULL;
		$this->load->vars(array('me' => $this->me));
	}

	protected function render($view, array $data = array())
	{
		$data['content'] = $this->load->view($view, $data, TRUE);
		$this->load->view('layouts/'.$this->layout, $data);
	}

	protected function flash($type, $message)
	{
		$this->session->set_flashdata('flash', array('type' => $type, 'message' => $message));
	}

	protected function require_post()
	{
		if ($this->input->method() !== 'post')
		{
			show_error('Metode tidak diizinkan.', 405);
		}
	}

	/** Wajib login; bila belum, simpan halaman tujuan lalu arahkan ke login */
	protected function require_login($role = NULL)
	{
		if ( ! $this->me)
		{
			$this->session->set_userdata('setelah_login', uri_string());
			$this->flash('warning', 'Silakan login terlebih dahulu.');
			redirect('login');
		}
		if ($role && $this->me->role !== $role)
		{
			show_error('Halaman ini khusus '.$role.'.', 403, 'Akses ditolak');
		}
	}
}

/** Semua controller admin/ mewarisi ini */
class Admin_Controller extends MY_Controller
{
	protected $layout = 'admin';

	public function __construct()
	{
		parent::__construct();
		$this->require_login('admin');
	}
}
