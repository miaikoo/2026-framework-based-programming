# Tugas 9 PBKK — ShopEase Authentication System (CodeIgniter 3)

Tugas ini menambahkan **sistem akun** ke toko online **ShopEase** dari [Tugas 8](../8-ShopEase-Landing): registrasi, login, logout, dan pembatasan halaman berdasarkan peran **customer**, **operator**, dan **admin**. Semua dibangun dengan **CodeIgniter 3.1.13** dan **MySQL** (XAMPP).

Alurnya: data pengguna disimpan di MySQL lewat **Model**, password di-hash dengan `password_hash()`, identitas pengguna dijaga lewat **Session**, lalu sebuah **hook** memeriksa peran sebelum **Controller** menampilkan **View**.

> Versi siap-tempel untuk Blogspot ada di [`blogspot.html`](blogspot.html) — Blogger → New Post → ikon pensil → **HTML view** → paste seluruh isinya.

![Halaman masuk](screenshots/s9-01-login.png)

## Fitur

| | Fitur |
|---|---|
| **Inti** | Registrasi dengan validasi per kolom, login, logout (POST + CSRF), password di-hash, sesi berganti ID setelah login |
| **Hak akses** | Peta akses di `config/akses.php` + hook `Penjaga_akses` (pengganti Filter CI4). Tamu → `/login`, peran yang tidak berhak → **halaman 403** |
| **Peran** | Customer: `/akun`. Operator: `/operator` (stok menipis & data pelanggan). Admin: `/admin` + `/admin/pengguna` (ubah peran, hapus akun) |
| **Ingat saya** | Token *selector + validator* di tabel `remember_tokens`; hanya hash validator yang disimpan, token dirotasi setiap dipakai, dan dicabut saat password diganti |
| **Batas percobaan** | 5 kali gagal dalam 15 menit untuk email + IP yang sama mengunci login 5 menit. Dicatat di tabel `login_attempts`, yang juga menjadi log aktivitas di dashboard admin |
| **Lainnya** | Kembali ke halaman tujuan setelah login, ganti password, "lupakan semua perangkat", peran berlaku langsung tanpa login ulang, tampilan mobile |

## Cara menjalankan

1. Start **Apache** dan **MySQL** di XAMPP.
2. Import `database/db_shopease.sql` lewat phpMyAdmin (tab *Import*), atau:
   ```bash
   mysql -u root < database/db_shopease.sql
   ```
3. Letakkan folder di `htdocs` (misalnya junction `htdocs\pbkk9` → folder ini), lalu buka **http://localhost/pbkk9/shopease/**

| Peran | Email | Password |
|---|---|---|
| Admin | `admin@shopease.test` | `shopease123` |
| Operator | `operator@shopease.test` | `shopease123` |
| Customer | `raka@shopease.test`, `laras@shopease.test`, `yosef@shopease.test` | `shopease123` |

> CodeIgniter 3.1.13 dirilis sebelum PHP 8.2, sehingga di PHP 8.5 muncul pesan *Deprecated*. Pesan itu hanya peringatan dan disembunyikan lewat `error_reporting()` di `index.php`.

## Struktur yang ditambahkan dari Tugas 8

```
9-ShopEase-Auth/
├── database/db_shopease.sql          ← tabel users, login_attempts, remember_tokens + akun contoh
└── shopease/application/
    ├── config/akses.php              ← peta hak akses, batas percobaan login, umur "Ingat saya"
    ├── config/hooks.php              ← mendaftarkan hook Penjaga_akses
    ├── hooks/Penjaga_akses.php       ← "filter" sebelum controller dijalankan
    ├── libraries/Autentikasi.php     ← login, logout, Ingat saya, batas percobaan (dipanggil $this->auth)
    ├── models/User_model.php
    ├── models/Login_attempt_model.php
    ├── models/Remember_token_model.php
    ├── controllers/Auth.php          ← /register, /login, /logout
    ├── controllers/Akun.php          ← /akun (profil, ganti password, lupakan perangkat)
    ├── controllers/Operator.php      ← /operator
    ├── controllers/Admin.php         ← /admin, /admin/pengguna
    └── views/auth/*, akun/*, admin/*, operator/*, halaman/ditolak.php, partials/panel_nav.php
```

## Langkah 1. Database `db_shopease`

```sql
CREATE TABLE users (
    id_user        INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,
    role           ENUM('customer','admin','operator') NOT NULL DEFAULT 'customer',
    phone          VARCHAR(20),
    address        TEXT,
    last_login_at  DATETIME NULL,
    created_at     DATETIME NULL,
    updated_at     DATETIME NULL
);
```

Ada dua tabel pendukung:
- **`login_attempts`** mencatat setiap percobaan login (email, IP, berhasil/gagal, waktu).
- **`remember_tokens`** menyimpan token "Ingat saya" (`selector`, `validator_hash`, `expires_at`). Token ikut terhapus saat akun dihapus karena `ON DELETE CASCADE`.

## Langkah 2. Koneksi & konfigurasi

| File | Pengaturan |
|---|---|
| `config/database.php` | `db_shopease`, user `root` tanpa password, `utf8mb4` |
| `config/autoload.php` | `database`, `session`, `form_validation`, `'autentikasi' => 'auth'` |
| `config/config.php` | `enable_hooks = TRUE`, `csrf_protection = TRUE`, `cookie_httponly = TRUE` |

Library diberi nama `Autentikasi` dan alias `auth`, karena nama class `Auth` sudah dipakai controller `Auth`. Kalau sama, PHP akan error karena satu nama class dideklarasikan dua kali.

## Langkah 3. Model

- **`User_model`** — `cari_by_email()` dipakai saat login. `daftar()` meng-hash password dan **selalu memberi peran `customer`**, karena peran ditentukan server, bukan dari form. Daftar pengguna untuk admin/operator hanya memilih kolom publik (`KOLOM_PUBLIK`) tanpa `password`.
- **`Login_attempt_model`** — `terkunci_sampai()` menghitung kegagalan dalam 15 menit terakhir setelah login berhasil yang terakhir.
- **`Remember_token_model`** — `buat()` menghasilkan `selector` (24 karakter) dan `validator` (64 karakter) dari `random_bytes()`, lalu hanya menyimpan `hash('sha256', validator)`.

## Langkah 4. Controller `Auth`

**Registrasi** — aturan validasi:

```php
$this->form_validation->set_rules('name', 'Nama', 'trim|required|min_length[3]|max_length[100]');
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]|is_unique[users.email]');
// aturan berbentuk array karena regex mengandung "|" (string aturan CI3 dipecah dengan "|")
$this->form_validation->set_rules('phone', 'Nomor HP', array('trim', 'regex_match[/^(\+62|62|0)8[0-9]{7,11}$/]'));
$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[72]|callback__password_kuat');
$this->form_validation->set_rules('password_confirm', 'Ulangi password', 'required|matches[password]');
```

> **Jebakan CI3:** string aturan dipecah dengan `|`, sehingga regex `(\+62|62|0)` ikut terpotong. Solusinya, aturan ditulis sebagai **array**. Bug ini sempat muncul saat pengujian ("Pesan kesalahan untuk Nomor HP belum diatur") lalu diperbaiki.

Setelah lolos validasi, akun langsung masuk (`$this->auth->masuk()`) dan diarahkan ke `/akun`.

**Login** — urutannya: cek terkunci → `verifikasi()` → catat percobaan → `masuk()`.
- `verifikasi()` tetap menjalankan `password_verify()` terhadap hash pembanding walau email tidak terdaftar, supaya lama prosesnya sama. Pesannya juga selalu sama: *"Email atau password salah."*
- Saat sisa percobaan tinggal ≤ 3, pengguna diberi tahu. Percobaan saat terkunci tidak dicatat, sehingga kuncian tidak terus diperpanjang.
- Setelah berhasil, pengguna kembali ke halaman yang tadi ingin dibuka (`tujuan_login`) atau ke beranda perannya.

**Logout** — hanya menerima POST dengan token CSRF. Token "Ingat saya" milik perangkat ini dihapus, cookie dihapus, dan ID sesi diganti.

### Library `Autentikasi`

```php
public function masuk($user, $ingat = FALSE)
{
    $this->CI->session->sess_regenerate(TRUE);                 // cegah session fixation
    $this->CI->session->set_userdata(array('id_user' => (int) $user->id_user, 'masuk_pada' => time()));
    $this->CI->user_model->catat_login($user->id_user);
    if ($ingat) { /* buat token selector:validator & pasang cookie httponly */ }
}
```

Session **hanya menyimpan `id_user`**; data pengguna dan perannya dibaca ulang dari database setiap request. Karena itu, ketika admin mengubah peran seseorang, perubahan langsung berlaku. Akun yang dihapus juga langsung tidak bisa mengakses apa pun.

## Langkah 5. Route

```php
$route['register'] = 'auth/register';   // GET = form, POST = simpan
$route['login']    = 'auth/login';      // GET = form, POST = periksa
$route['logout']   = 'auth/logout';     // hanya POST
```

`/akun`, `/operator`, dan `/admin/...` memakai routing otomatis CI3 (controller/method). Penjagaannya **tidak ditulis di route**, melainkan di peta akses.

## Langkah 6. Penjaga akses (pengganti Filter CI4)

CodeIgniter 3 tidak punya Filter. Sebagai gantinya dipakai **hook `post_controller_constructor`**, yang berjalan setelah controller dibuat tetapi sebelum method-nya dipanggil:

```php
// config/akses.php
$config['akses'] = array(
    'akun'     => 'login',                       // semua peran yang sudah login
    'operator' => array('operator', 'admin'),
    'admin'    => array('admin'),
);
$config['khusus_tamu'] = array('login', 'register');
```

`hooks/Penjaga_akses.php` membaca peta ini:
1. Halaman khusus tamu + sudah login → dialihkan ke beranda perannya.
2. Halaman dijaga + belum login → URL tujuan disimpan, lalu dialihkan ke `/login` dengan pesan.
3. Sudah login tetapi perannya tidak diizinkan → **halaman 403** (status HTTP 403) yang menjelaskan halaman itu khusus siapa.

Menambah halaman terlindungi cukup dengan satu baris di `config/akses.php`, tanpa mengubah controller.

## Langkah 7. View

- **Masuk & daftar**: form di kiri, panel navy dengan label harga gantung khas ShopEase di kanan (disembunyikan di layar kecil). Ada tombol **Lihat** untuk password, dan kolom HP/alamat dikelompokkan sebagai "boleh diisi nanti".
- **Akun**: data diri, ganti password, **peta akses** (halaman yang boleh/tidak boleh dibuka sesuai peran), dan info sesi & perangkat yang diingat.
- **Admin**: angka ringkasan, pendaftar terbaru, log aktivitas login, serta tabel kelola pengguna yang berubah menjadi kartu di layar HP.
- Semua output memakai `html_escape()`, semua form POST memakai `form_open()` (token CSRF), dan password tidak pernah dikembalikan ke form.

## Hasil pengujian

Pengujian dijalankan otomatis dengan Playwright di browser Edge terhadap aplikasi yang berjalan di XAMPP. **23 dari 23 pemeriksaan lulus**:

| Skenario | Hasil |
|---|---|
| POST `/logout` tanpa token CSRF | ditolak 403 |
| Registrasi: email terdaftar, password tanpa angka | ditolak dengan pesan per kolom; password tidak dikembalikan |
| Registrasi valid | langsung masuk ke `/akun` |
| Customer membuka `/admin` dan `/operator` | 403 |
| Sudah login membuka `/login` | dialihkan ke `/akun` |
| Logout, lalu membuka `/akun` | dialihkan ke `/login` |
| 4× password salah | muncul "Sisa 1 percobaan" |
| 5× salah lalu password benar | tetap ditolak (terkunci); email lain tidak ikut terkunci |
| Tamu membuka `/admin/pengguna` → login admin | kembali ke `/admin/pengguna` |
| Admin mengubah peran Dewi → operator | Dewi login diarahkan ke `/operator`, ditolak di `/admin` |
| "Ingat saya" + sesi dihapus | masuk otomatis, nilai cookie berganti (rotasi) |
| Memakai cookie lama yang sudah dirotasi | ditolak |
| Ganti password | berhasil, perangkat yang diingat dikeluarkan |

| | |
|---|---|
| Masuk<br>![](screenshots/s9-01-login.png) | Daftar<br>![](screenshots/s9-02-register.png) |
| Registrasi tidak valid<br>![](screenshots/s9-03-register-gagal.png) | Registrasi berhasil → akun customer<br>![](screenshots/s9-04-akun-customer.png) |
| Customer membuka /admin → 403<br>![](screenshots/s9-05-customer-ditolak-admin.png) | Menu akun<br>![](screenshots/s9-06-menu-akun-customer.png) |
| Logout<br>![](screenshots/s9-07-logout.png) | Sisa percobaan login<br>![](screenshots/s9-08-login-gagal-sisa.png) |
| Login terkunci<br>![](screenshots/s9-09-login-terkunci.png) | Admin: kelola pengguna (kembali ke halaman tujuan)<br>![](screenshots/s9-10-admin-kelola-pengguna.png) |
| Dashboard admin + log login<br>![](screenshots/s9-11-admin-dashboard.png) | Ubah peran<br>![](screenshots/s9-12-admin-ubah-peran.png) |
| Dashboard operator<br>![](screenshots/s9-13-operator-dashboard.png) | Operator membuka /admin → 403<br>![](screenshots/s9-14-operator-ditolak-admin.png) |
| Masuk otomatis lewat "Ingat saya"<br>![](screenshots/s9-15-ingat-saya-otomatis.png) | Ganti password gagal<br>![](screenshots/s9-16-ganti-password-gagal.png) |
| Ganti password berhasil<br>![](screenshots/s9-17-ganti-password-berhasil.png) | Mobile: masuk<br>![](screenshots/s9-18-mobile-login.png) |
| Mobile: kelola pengguna (kartu)<br>![](screenshots/s9-19-mobile-kelola-pengguna.png) | Tabel users di phpMyAdmin<br>![](screenshots/s9-20-phpmyadmin-users.png) |
| Tabel login_attempts<br>![](screenshots/s9-21-phpmyadmin-login-attempts.png) | |

## Kesimpulan

Sistem autentikasi ShopEase menunjukkan bahwa CodeIgniter 3 tetap bisa menerapkan pola yang di CodeIgniter 4 dikerjakan oleh Filter, yaitu dengan **hook** dan **peta akses di file config**. Password aman karena di-hash dan diverifikasi dengan `password_verify()`. Sesi dilindungi dengan pergantian ID setelah login dan cookie `httponly`. Semua aksi yang mengubah data memakai POST + CSRF. Dua tabel pendukung membuat fitur tambahan lebih aman dan bisa diaudit: `login_attempts` untuk batas percobaan dan log, serta `remember_tokens` untuk "Ingat saya" yang bisa dirotasi dan dicabut.
