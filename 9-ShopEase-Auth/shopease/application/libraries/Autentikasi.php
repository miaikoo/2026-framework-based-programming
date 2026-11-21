<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library autentikasi ShopEase.
 * Session hanya menyimpan id_user; data pengguna (termasuk peran) dibaca ulang
 * dari database setiap request, sehingga perubahan peran oleh admin atau akun
 * yang dihapus langsung berlaku tanpa menunggu pengguna logout.
 */
class Autentikasi
{
	const COOKIE = 'shopease_ingat';

	/** hash palsu agar waktu proses email tak terdaftar sama dengan password salah */
	const HASH_PEMBANDING = '$2y$12$nBPu69f4NoWHzA0bQJRrmuOcWJxw5PUR8RQuUefrXyevXIpCg8AIW';

	/** @var CI_Controller */
	protected $CI;
	protected $user = FALSE;   // FALSE = belum dibaca, NULL = tamu

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('akses');
		$this->CI->load->model(array('user_model', 'remember_token_model', 'login_attempt_model'));
		$this->CI->load->helper('cookie');
		// masuk otomatis dari cookie "Ingat saya" bila sesi sudah habis
		$this->_masuk_dari_cookie();
	}

	/* ---------------- status ---------------- */

	public function cek()
	{
		return $this->user() !== NULL;
	}

	/** @return object|null pengguna yang sedang login (tanpa password) */
	public function user()
	{
		if ($this->user === FALSE)
		{
			$id = $this->CI->session->userdata('id_user');
			$u = $id ? $this->CI->user_model->cari($id) : NULL;
			if ($id && ! $u)
			{
				// akun sudah dihapus admin: bersihkan sesi
				$this->CI->session->unset_userdata(array('id_user', 'masuk_pada'));
			}
			if ($u) unset($u->password);
			$this->user = $u ?: NULL;
		}
		return $this->user;
	}

	public function peran()
	{
		return $this->cek() ? $this->user()->role : NULL;
	}

	public function beranda_peran($peran = NULL)
	{
		$map = $this->CI->config->item('beranda_peran');
		$peran = $peran ?: $this->peran();
		return isset($map[$peran]) ? $map[$peran] : '';
	}

	/* ---------------- login / logout ---------------- */

	/**
	 * Periksa email & password.
	 * @return object|false user bila cocok
	 */
	public function verifikasi($email, $password)
	{
		$user = $this->CI->user_model->cari_by_email($email);
		// password_verify tetap dijalankan walau email tidak ada, agar lama proses sama
		$cocok = password_verify((string) $password, $user ? $user->password : self::HASH_PEMBANDING);
		return ($user && $cocok) ? $user : FALSE;
	}

	public function masuk($user, $ingat = FALSE)
	{
		// ID sesi diganti setelah login untuk mencegah session fixation
		$this->CI->session->sess_regenerate(TRUE);
		$this->CI->session->set_userdata(array('id_user' => (int) $user->id_user, 'masuk_pada' => time()));
		$this->CI->user_model->catat_login($user->id_user);
		$this->user = FALSE;

		if ($ingat)
		{
			$hari = (int) $this->CI->config->item('ingat_saya_hari');
			$this->_pasang_cookie($this->CI->remember_token_model->buat($user->id_user, $hari), $hari * 86400);
		}
	}

	public function keluar()
	{
		$selector = $this->_selector_cookie();
		if ($selector) $this->CI->remember_token_model->hapus_selector($selector);
		delete_cookie(self::COOKIE);
		$this->CI->session->unset_userdata(array('id_user', 'masuk_pada'));
		$this->CI->session->sess_regenerate(TRUE);
		$this->user = NULL;
	}

	/** Lupakan semua perangkat (hapus semua token Ingat saya milik pengguna) */
	public function lupakan_semua_perangkat($id_user)
	{
		delete_cookie(self::COOKIE);
		return $this->CI->remember_token_model->hapus_milik($id_user);
	}

	public function diingat()
	{
		return $this->_selector_cookie() !== NULL;
	}

	/* ---------------- pembatasan percobaan ---------------- */

	public function terkunci_sampai($email)
	{
		$c = $this->CI->config;
		return $this->CI->login_attempt_model->terkunci_sampai($email, $this->CI->input->ip_address(),
			$c->item('login_batas_gagal'), $c->item('login_jendela_menit'), $c->item('login_kunci_menit'));
	}

	public function catat_percobaan($email, $berhasil)
	{
		$this->CI->login_attempt_model->catat($email, $this->CI->input->ip_address(), $berhasil, $this->CI->input->user_agent());
	}

	public function sisa_percobaan($email)
	{
		return $this->CI->login_attempt_model->sisa_percobaan($email, $this->CI->input->ip_address(),
			$this->CI->config->item('login_batas_gagal'), $this->CI->config->item('login_jendela_menit'));
	}

	/* ---------------- internal: cookie Ingat saya ---------------- */

	private function _masuk_dari_cookie()
	{
		if ($this->CI->session->userdata('id_user')) return;

		$nilai = (string) get_cookie(self::COOKIE);
		if ( ! preg_match('/^([a-f0-9]{24}):([a-f0-9]{64})$/', $nilai, $m)) return;

		$token = $this->CI->remember_token_model->cari_selector($m[1]);
		$sah = $token && $token->expires_at > date('Y-m-d H:i:s')
			&& hash_equals($token->validator_hash, hash('sha256', $m[2]));

		if ( ! $sah)
		{
			// selector cocok tetapi validator salah = kemungkinan cookie dicuri: cabut semua token pengguna itu
			if ($token) $this->CI->remember_token_model->hapus_milik($token->id_user);
			delete_cookie(self::COOKIE);
			return;
		}

		$user = $this->CI->user_model->cari($token->id_user);
		if ( ! $user) return;

		// token sekali pakai: hapus yang lama, masuk() akan membuat token baru (rotasi)
		$this->CI->remember_token_model->hapus($token->id);
		$this->masuk($user, TRUE);
		$this->CI->session->set_flashdata('info', 'Selamat datang kembali, '.$user->name.'. Kamu masuk otomatis lewat "Ingat saya".');
	}

	private function _selector_cookie()
	{
		$nilai = (string) get_cookie(self::COOKIE);
		return preg_match('/^([a-f0-9]{24}):/', $nilai, $m) ? $m[1] : NULL;
	}

	private function _pasang_cookie($nilai, $detik)
	{
		$this->CI->input->set_cookie(array(
			'name'     => self::COOKIE,
			'value'    => $nilai,
			'expire'   => $detik,
			'httponly' => TRUE,
		));
	}
}
