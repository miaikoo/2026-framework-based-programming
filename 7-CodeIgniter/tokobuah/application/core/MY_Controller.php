<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller dasar. CodeIgniter otomatis memuat file application/core/MY_Controller.php
 * karena memakai prefix "MY_" (lihat $config['subclass_prefix']).
 */
class MY_Controller extends CI_Controller
{
}

/**
 * Semua controller di folder admin/ mewarisi class ini, jadi pengecekan login
 * cukup ditulis sekali di sini, tidak diulang di setiap controller.
 */
class Admin_Controller extends MY_Controller
{
	/** @var object data user yang sedang login */
	protected $user;

	public function __construct()
	{
		parent::__construct();

		if ( ! $this->session->userdata('logged_in'))
		{
			$this->session->set_flashdata('warning', 'Silakan login terlebih dahulu.');
			redirect('login');
		}

		$this->user = (object) array(
			'id'        => $this->session->userdata('user_id'),
			'username'  => $this->session->userdata('username'),
			'full_name' => $this->session->userdata('full_name'),
			'role'      => $this->session->userdata('role'),
		);
		// tersedia di semua view sebagai $user
		$this->load->vars(array('user' => $this->user));
	}

	/** Hanya role tertentu yang boleh melanjutkan. */
	protected function only_role($role)
	{
		if ($this->user->role !== $role)
		{
			$this->session->set_flashdata('danger', 'Akses ditolak: fitur ini khusus '.$role.'.');
			redirect('admin/products');
		}
	}
}
