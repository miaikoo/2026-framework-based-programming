# Tugas 7 PBKK — CodeIgniter 3: Tokobuah, SIAKAD Presensi & MerchHima

Tugas ini berisi tiga aplikasi web yang dibangun dengan **CodeIgniter 3.1.13**, **PHP**, dan **MySQL/MariaDB** di atas **XAMPP**:

| | Aplikasi | Isi | Template |
|---|---|---|---|
| **Latihan 1** | **Tokobuah** | Dasar-dasar CodeIgniter (materi PBKK 2-1 s.d. 2-7): instalasi, routing, view, template admin, model & CRUD, upload file, login | SB Admin 7 (Bootstrap 5) |
| **Latihan 2a** | **SIAKAD Presensi** | Sistem akademik dengan fokus **presensi kuliah memakai kode**, rekap kehadiran, dan kelayakan UAS (min. 75%) untuk 3 role: admin, dosen, mahasiswa | AdminLTE 3 |
| **Latihan 2b** | **MerchHima** | Toko online **preorder merchandise himpunan**: batch PO bertenggat, pilihan ukuran S–XXL, upload bukti transfer, verifikasi admin, rekap produksi per ukuran | AdminLTE 3 (top-nav + sidebar) |

> Versi siap-tempel untuk Blogspot ada di [`blogspot.html`](blogspot.html) — Blogger → New Post → ikon pensil → **HTML view** → paste seluruh isinya.

## Daftar Isi
1. [Lingkungan & Cara Menjalankan](#1-lingkungan--cara-menjalankan)
2. [Struktur Folder](#2-struktur-folder)
3. [Latihan 1 — Tokobuah](#3-latihan-1--tokobuah)
4. [Latihan 2a — SIAKAD Presensi](#4-latihan-2a--siakad-presensi)
5. [Latihan 2b — MerchHima (Preorder Merchandise)](#5-latihan-2b--merchhima-preorder-merchandise)
6. [Ringkasan Konsep CodeIgniter yang Dipakai](#6-ringkasan-konsep-codeigniter-yang-dipakai)
7. [Kesimpulan](#7-kesimpulan)

---

## 1. Lingkungan & Cara Menjalankan

| Komponen | Versi yang dipakai saat pengujian |
|---|---|
| OS | Windows 11 |
| XAMPP | Apache 2.4 + MariaDB 10.11 + PHP 8.5 |
| Framework | CodeIgniter 3.1.13 |
| Browser uji | Microsoft Edge (screenshot diambil otomatis dengan Playwright) |

### Langkah
1. **Instal XAMPP**, buka *XAMPP Control Panel*, lalu **Start** Apache dan MySQL.
2. **Letakkan folder `7-CodeIgniter` di dalam `htdocs`.** Bisa disalin, atau dibuat *junction* agar kode tetap di folder repo:
   ```powershell
   # PowerShell, sesuaikan lokasi XAMPP
   New-Item -ItemType Junction -Path "C:\xampp\htdocs\pbkk7" -Target "D:\...\7-CodeIgniter"
   ```
3. **Import database** lewat phpMyAdmin (tab *Import*) atau terminal:
   ```bash
   cd 7-CodeIgniter/database
   mysql -u root < tokobuah.sql
   mysql -u root < siakad.sql
   mysql -u root < merch_po.sql
   ```
4. **Buka di browser:**

| Aplikasi | URL | Akun demo |
|---|---|---|
| Tokobuah | http://localhost/pbkk7/tokobuah/ | `admin` / `tokobuah123` (admin), `kasir` / `tokobuah123` (staff) |
| SIAKAD | http://localhost/pbkk7/siakad/ | `admin`, dosen `0011087801`, mahasiswa `2310101001` — semua password `siakad123` |
| MerchHima | http://localhost/pbkk7/merch-po/ | `admin@merchhima.test`, pembeli `aditya@student.kampus.test` — password `merch123` |

### Konfigurasi yang diubah dari bawaan CodeIgniter
| File | Perubahan | Alasan |
|---|---|---|
| `index.php` | `$system_path = '../system';` | Ketiga aplikasi memakai **satu folder `system/`** bersama |
| `index.php` | `error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED)` | CI 3.1.13 dirilis sebelum PHP 8.2, sehingga di PHP 8.5 muncul banyak pesan *Deprecated* (mis. *dynamic property*). Pesan ini hanya peringatan, bukan error |
| `config/config.php` | `base_url` dihitung otomatis dari `$_SERVER` | Aplikasi bisa diletakkan di folder mana pun tanpa ubah konfigurasi |
| `config/config.php` | `index_page = ''` + file `.htaccess` | URL bersih tanpa `index.php` (butuh `mod_rewrite`) |
| `config/config.php` | `csrf_protection = TRUE` | Semua form POST wajib membawa token CSRF (otomatis dari `form_open()`) |
| `config/config.php` | `language = 'indonesian'` + `application/language/indonesian/` | Pesan validasi & upload berbahasa Indonesia |
| `config/config.php` | `date_default_timezone_set('Asia/Jakarta')` | Waktu presensi/pesanan sesuai WIB |
| `config/database.php` | `root` tanpa password, `utf8mb4` | Default XAMPP |
| `config/autoload.php` | `database`, `session`, `form_validation`, helper `url`, `form`, `html` | Dipakai di hampir semua halaman |

## 2. Struktur Folder

```
7-CodeIgniter/
├── system/                    ← inti CodeIgniter 3.1.13 (dipakai bersama)
├── database/
│   ├── tokobuah.sql
│   ├── siakad.sql
│   └── merch_po.sql
├── tokobuah/                  ← Latihan 1
│   ├── index.php, .htaccess
│   ├── assets/sb-admin/       ← CSS & JS SB Admin 7
│   ├── upload/product/        ← gambar produk
│   └── application/
│       ├── config/  core/MY_Controller.php  helpers/rupiah_helper.php
│       ├── controllers/  Home.php  Auth.php  admin/Overview.php  admin/Products.php
│       ├── models/       Product_model.php  User_model.php
│       └── views/        home/  auth/  admin/_partials/  admin/products/
├── siakad/                    ← Latihan 2a
│   └── application/
│       ├── core/MY_Controller.php  core/MY_Model.php  helpers/siakad_helper.php
│       ├── controllers/  Auth  Dashboard  Profil  admin/*  dosen/Mengajar  mahasiswa/*
│       ├── models/       User  Mahasiswa  Dosen  Matakuliah  Kelas  Pertemuan  Presensi  Pengumuman
│       └── views/        layouts/app.php  dashboard/  admin/  dosen/  mahasiswa/
├── merch-po/                  ← Latihan 2b
│   ├── upload/produk/         ← foto produk (publik)
│   ├── storage/bukti/         ← bukti transfer (PRIVAT, diblokir .htaccess)
│   └── application/
│       ├── libraries/Keranjang_belanja.php
│       ├── controllers/  Toko  Auth  Keranjang  Pesanan  admin/*
│       ├── models/       User  Batch  Produk  Pesanan
│       └── views/        layouts/toko.php  layouts/admin.php  ...
├── screenshots/
├── README.md
└── blogspot.html
```

---

## 3. Latihan 1 — Tokobuah

Toko buah sederhana: halaman katalog untuk pengunjung dan panel admin untuk mengelola produk. Setiap materi PBKK 2-1 s.d. 2-7 diwujudkan sebagai berikut:

| Materi | Implementasi di Tokobuah | File utama |
|---|---|---|
| 2-1 Instalasi & konfigurasi | Download CI 3.1.13, atur `base_url`, `index_page`, database, autoload, `.htaccess` | `config/*.php`, `.htaccess` |
| 2-2 Controller & routing | `Home` sebagai `default_controller`, route custom `produk/(:num)`, `login`, `logout`, `admin` | `config/routes.php`, `controllers/Home.php` |
| 2-3 View & Bootstrap | Katalog dengan kartu produk, pencarian, filter kategori, halaman detail | `views/home/*` |
| 2-4 Template admin | SB Admin 7 dipecah menjadi *partial*: `head`, `navbar`, `sidebar`, `breadcrumb`, `alert`, `footer`, `js` | `views/admin/_partials/*` |
| 2-5 Model & CRUD | `Product_model` (Query Builder) + `form_validation`, satu view form untuk tambah & edit | `models/Product_model.php`, `controllers/admin/Products.php` |
| 2-6 Upload file | Library `upload` (jpg/png/webp, maks. 2 MB), pratinjau gambar, hapus file lama saat diganti/dihapus | `Products::_upload_image()` |
| 2-7 Login & hak akses | `password_hash`/`password_verify`, session, `Admin_Controller` di `core/MY_Controller.php`, role **staff tidak bisa menghapus** | `controllers/Auth.php`, `core/MY_Controller.php` |

### Hasil

| | |
|---|---|
| Katalog<br>![](screenshots/l1-01-home.png) | Pencarian "jeruk"<br>![](screenshots/l1-02-cari.png) |
| Filter kategori Impor<br>![](screenshots/l1-03-kategori-impor.png) | Detail produk<br>![](screenshots/l1-04-detail.png) |
| Akses admin tanpa login → diarahkan ke login<br>![](screenshots/l1-05-wajib-login.png) | Password salah<br>![](screenshots/l1-06-login-gagal.png) |
| Dashboard (kartu ringkasan + grafik stok)<br>![](screenshots/l1-07-dashboard.png) | Daftar produk (simple-datatables)<br>![](screenshots/l1-08-daftar-produk.png) |
| Validasi server (`form_validation`)<br>![](screenshots/l1-09-validasi.png) | Upload file selain gambar ditolak<br>![](screenshots/l1-10a-upload-ditolak.png) |
| Form tambah + pratinjau gambar<br>![](screenshots/l1-10-form-upload.png) | Produk berhasil ditambahkan<br>![](screenshots/l1-11-tambah-berhasil.png) |
| Edit produk<br>![](screenshots/l1-12-edit.png) | Edit berhasil<br>![](screenshots/l1-13-edit-berhasil.png) |
| Konfirmasi hapus (modal, form POST)<br>![](screenshots/l1-14-modal-hapus.png) | Hapus berhasil (gambar ikut terhapus)<br>![](screenshots/l1-15-hapus-berhasil.png) |
| Login sebagai staff: tombol Hapus tidak ada<br>![](screenshots/l1-16-role-staff.png) | Database di phpMyAdmin<br>![](screenshots/l1-17-phpmyadmin.png) |
| Isi tabel `products`<br>![](screenshots/l1-18-phpmyadmin-products.png) | |

### Penjelasan kode penting
- **`core/MY_Controller.php`** — CodeIgniter otomatis memuat file berawalan `MY_` dari folder `core/`. Di sini dibuat `Admin_Controller` yang memeriksa session di konstruktornya, sehingga **semua controller di `admin/` cukup `extends Admin_Controller`** tanpa menulis ulang pengecekan login. Method `only_role('admin')` dipakai untuk membatasi aksi hapus.
- **Satu form untuk tambah & edit** — `views/admin/products/form.php` menerima `$mode` dan `$product`. Fungsi `set_value()`, `set_select()`, dan `set_radio()` mengisi ulang input ketika validasi gagal.
- **Upload** — `_upload_image()` mengembalikan tiga kemungkinan: nama file (berhasil), `NULL` (tidak memilih file → gambar lama dipertahankan), atau `FALSE` (gagal → form ditampilkan lagi dengan pesan error). Nama file dibuat dari `url_title(nama)` + waktu agar unik.
- **Hapus memakai POST** — tombol Hapus membuka modal berisi `form_open()` sehingga ikut membawa token CSRF; method `delete()` menolak request selain POST.
- **Keamanan dasar** — password disimpan dengan `password_hash()`, `sess_regenerate(TRUE)` setelah login, input dicetak dengan `html_escape()`.

---

## 4. Latihan 2a — SIAKAD Presensi

SIAKAD ini difokuskan pada **presensi perkuliahan**. Dosen membuka sesi pertemuan dan sistem membuat **kode presensi 6 karakter** yang berlaku beberapa menit. Mahasiswa memasukkan kode tersebut untuk tercatat hadir. Saat sesi ditutup, peserta yang belum presensi otomatis dicatat **Alpa**. Rekap kehadiran menentukan siapa yang **boleh mengikuti UAS (kehadiran ≥ 75%)**.

### Rancangan database (`pbkk_siakad`)

```mermaid
erDiagram
    dosen ||--o{ kelas : mengampu
    matakuliah ||--o{ kelas : dibuka_sebagai
    kelas ||--o{ peserta_kelas : memiliki
    mahasiswa ||--o{ peserta_kelas : mengikuti
    kelas ||--o{ pertemuan : terdiri_dari
    pertemuan ||--o{ presensi : mencatat
    mahasiswa ||--o{ presensi : melakukan
    users }o--|| dosen : akun
    users }o--|| mahasiswa : akun
    users ||--o{ pengumuman : membuat
```

| Tabel | Keterangan |
|---|---|
| `users` | Akun login dengan `role` admin/dosen/mahasiswa, terhubung ke `dosen_id` atau `mahasiswa_id` |
| `mahasiswa`, `dosen`, `matakuliah` | Master data (NIM, NIDN, kode MK unik) |
| `kelas` | Mata kuliah yang dibuka + dosen + jadwal (hari, jam, ruang). UNIQUE (MK, nama kelas, tahun ajaran) |
| `peserta_kelas` | Relasi N–N mahasiswa ↔ kelas |
| `pertemuan` | Sesi tatap muka: `kode`, `status` (dibuka/ditutup), `dibuka_at`, `berakhir_at` |
| `presensi` | Status H/I/S/A per mahasiswa per pertemuan. UNIQUE (pertemuan, mahasiswa) |
| `pengumuman` | Pengumuman untuk semua / dosen / mahasiswa |
| **view** `v_rekap_kehadiran` | Jumlah H/I/S/A dan persentase kehadiran per mahasiswa per kelas |

### Alur presensi
```
Dosen: Buka sesi (topik + durasi) ──► kode acak 6 karakter, berlaku N menit
Mahasiswa: masukkan kode ──► cek: kode ada & sesi masih dibuka?  peserta kelas?  belum presensi?
                                         │ ya
                                         ▼
                                 presensi H + jam masuk
Dosen: ubah status (Izin/Sakit + keterangan) ──► Tutup sesi
                                                     │
                                                     ▼
                       INSERT IGNORE semua peserta yang belum tercatat sebagai A (Alpa)
                       (sesi yang lewat batas waktu juga ditutup otomatis)
```

### Fitur per role
| Admin (Bagian Akademik) | Dosen | Mahasiswa |
|---|---|---|
| Dashboard: jumlah data, **daftar mahasiswa < 75%**, sesi yang sedang dibuka | Dashboard: jadwal mengajar hari ini + tombol buka presensi | Dashboard: notifikasi sesi yang sedang dibuka, kuliah hari ini, ringkasan kehadiran |
| CRUD mahasiswa & dosen (akun login dibuat otomatis) | Daftar kelas yang diampu | Isi presensi dengan kode |
| CRUD mata kuliah | Buka sesi → kode presensi + hitung mundur | Jadwal kuliah mingguan |
| CRUD kelas & jadwal + atur peserta (checkbox) | Daftar hadir real-time, ubah status I/S/A + keterangan, tutup sesi | Rekap kehadiran per mata kuliah + status UAS |
| CRUD pengumuman (target: semua/dosen/mahasiswa) | Rekap matriks kehadiran (pertemuan × mahasiswa) + status UAS | Riwayat presensi per pertemuan |

Semua role bisa mengganti password di halaman **Profil**.

### Hasil

| | |
|---|---|
| Login<br>![](screenshots/l2s-01-login.png) | Dashboard admin<br>![](screenshots/l2s-02-dashboard-admin.png) |
| Data mahasiswa<br>![](screenshots/l2s-03-data-mahasiswa.png) | Validasi: NIM sudah dipakai & email tidak valid<br>![](screenshots/l2s-04-validasi-mahasiswa.png) |
| Mahasiswa ditambah + akun otomatis<br>![](screenshots/l2s-05-mahasiswa-ditambah.png) | Kelas & jadwal<br>![](screenshots/l2s-06-kelas-jadwal.png) |
| Atur peserta kelas<br>![](screenshots/l2s-07-peserta-kelas.png) | Pengumuman<br>![](screenshots/l2s-08-pengumuman.png) |
| Dashboard dosen (jadwal hari ini)<br>![](screenshots/l2s-09-dashboard-dosen.png) | Kelas yang diampu<br>![](screenshots/l2s-10-kelas-saya.png) |
| Form buka sesi + riwayat pertemuan<br>![](screenshots/l2s-11-buka-sesi.png) | **Kode presensi** dan daftar hadir<br>![](screenshots/l2s-12-kode-presensi.png) |
| Dashboard mahasiswa: ada sesi dibuka<br>![](screenshots/l2s-13-dashboard-mahasiswa.png) | Kode salah<br>![](screenshots/l2s-14-kode-salah.png) |
| Presensi berhasil<br>![](screenshots/l2s-15-presensi-berhasil.png) | Kode dipakai dua kali<br>![](screenshots/l2s-16-sudah-presensi.png) |
| Daftar hadir terisi<br>![](screenshots/l2s-17-daftar-hadir.png) | Sesi ditutup: sisanya otomatis Alpa, Galih diubah Sakit<br>![](screenshots/l2s-18-sesi-ditutup.png) |
| Rekap kelas & kelayakan UAS<br>![](screenshots/l2s-19-rekap-kelas.png) | Rekap kehadiran mahasiswa<br>![](screenshots/l2s-20-rekap-mahasiswa.png) |
| Riwayat presensi per pertemuan<br>![](screenshots/l2s-21-riwayat-presensi.png) | Jadwal mingguan<br>![](screenshots/l2s-22-jadwal-mingguan.png) |
| Database di phpMyAdmin<br>![](screenshots/l2s-23-phpmyadmin.png) | |

### Penjelasan kode penting
- **`core/MY_Model.php`** — model dasar berisi `all()`, `find()`, `insert()`, `update()`, `delete()`, `count()`, `dropdown()`. Model lain cukup mengisi `$table`, sehingga kode CRUD tidak ditulis berulang. CodeIgniter 3 otomatis memuat `MY_Model` setiap kali model di-load.
- **Layout dengan `render()`** — berbeda dengan Latihan 1 yang memanggil partial satu per satu, di sini `MY_Controller::render()` me-*render* view halaman ke string (`$this->load->view($view, $data, TRUE)`) lalu menyisipkannya ke `layouts/app.php`. View halaman jadi pendek dan hanya berisi konten.
- **Hak akses berbasis class** — `Auth_Controller` mewajibkan login dan memeriksa properti `$role`. Turunannya `Admin_Controller`, `Dosen_Controller`, dan `Mahasiswa_Controller` cukup menetapkan role. Selain itu ada **otorisasi data**: dosen hanya bisa membuka kelas miliknya (`_kelas_saya()`), mahasiswa hanya bisa melihat kelas yang ia ikuti. Akses yang tidak sah menghasilkan 403/404.
- **Kode presensi** (`Pertemuan_model::generate_kode()`) — memakai `random_int()` dan alfabet tanpa huruf yang mirip (O/0, I/1), dan dipastikan tidak bentrok dengan sesi lain yang masih dibuka.
- **Tutup sesi = transaksi** (`Pertemuan_model::tutup()`) — status sesi diubah lalu `INSERT IGNORE ... SELECT` mencatat Alpa untuk peserta yang belum presensi. Constraint `UNIQUE(pertemuan_id, mahasiswa_id)` menjaga data yang sudah ada tidak tertimpa.
- **Persentase kehadiran** dihitung oleh view `v_rekap_kehadiran` dari pertemuan yang sudah ditutup dan tercatat untuk mahasiswa tersebut. Karena saat penutupan semua peserta pasti tercatat (minimal Alpa), mahasiswa yang baru ditambahkan di tengah semester tidak dianggap absen pada pertemuan sebelumnya.
- **Callback validasi** — `_unique_except` (NIM/NIDN/email unik kecuali data yang sedang diedit), `_setelah_jam_mulai` (jam selesai > jam mulai), `_cek_password_lama`. Nama berawalan `_` sehingga tidak bisa diakses lewat URL.

---

## 5. Latihan 2b — MerchHima (Preorder Merchandise)

Toko online untuk **preorder merchandise himpunan mahasiswa**. Berbeda dari toko biasa yang menjual barang berstok, di sini barang **baru diproduksi setelah PO ditutup**. Karena itu yang penting adalah tenggat PO, pilihan ukuran, verifikasi pembayaran, dan rekap jumlah produksi untuk vendor.

### Rancangan database (`pbkk_merchpo`)

```mermaid
erDiagram
    batch_po ||--o{ produk : berisi
    batch_po ||--o{ pesanan : untuk
    users ||--o{ pesanan : membuat
    pesanan ||--|{ pesanan_item : terdiri_dari
    produk ||--o{ pesanan_item : dipesan
```

| Tabel | Keterangan |
|---|---|
| `users` | Admin & pembeli (registrasi mandiri, nomor WhatsApp untuk konfirmasi) |
| `batch_po` | Gelombang PO: `dibuka_at`, `ditutup_at`, estimasi pengambilan, rekening tujuan. Status *segera/dibuka/ditutup* **dihitung dari tanggal**, jadi PO tertutup otomatis |
| `produk` | Harga, foto, `pakai_ukuran` (wajib pilih S–XXL), `kuota` (NULL = tanpa batas) |
| `pesanan` | Kode pesanan, total, status, catatan, nama file bukti, alasan penolakan, waktu bayar & verifikasi |
| `pesanan_item` | Produk, ukuran, qty, **harga saat checkout** (tidak berubah walau harga produk diedit) |

### Alur status pesanan
```
menunggu_pembayaran ──(pembeli upload bukti)──► menunggu_verifikasi ──(admin: verifikasi)──► lunas ──► siap_diambil ──► selesai
        │                                              │
        └──(pembeli batal)──► dibatalkan               └──(admin: tolak + alasan)──► ditolak ──(upload ulang)──► menunggu_verifikasi
```
Transisi yang sah disimpan di konstanta `Pesanan_model::TRANSISI`, sehingga admin tidak bisa, misalnya, menandai "siap diambil" pesanan yang belum lunas.

### Fitur
| Pembeli | Admin |
|---|---|
| Katalog batch yang dibuka + **hitung mundur** penutupan, info batch berikutnya | Dashboard: pesanan perlu verifikasi, belum bayar, dana masuk, **grafik terjual** (Chart.js) |
| Detail produk: pilih ukuran, *size chart*, sisa kuota | Daftar pesanan dengan filter status & batch |
| Keranjang (session) — hanya satu batch per pesanan | Cek bukti transfer, **verifikasi** atau **tolak dengan alasan** |
| Checkout (wajib login; keranjang tetap tersimpan saat login/daftar) | Tandai siap diambil → selesai |
| Upload bukti transfer (JPG/PNG/PDF ≤ 2 MB), upload ulang bila ditolak | **Rekap produksi per ukuran** + daftar pengambilan + **unduh CSV** untuk vendor |
| Timeline status pesanan, batalkan pesanan yang belum dibayar | CRUD batch PO & produk (upload foto, kuota, opsi ukuran) |

### Hasil

| | |
|---|---|
| Katalog + hitung mundur PO<br>![](screenshots/l2m-01-katalog.png) | Detail produk, pilih ukuran, size chart<br>![](screenshots/l2m-02-detail-produk.png) |
| Keranjang<br>![](screenshots/l2m-03-keranjang.png) | Checkout harus login<br>![](screenshots/l2m-04-checkout-harus-login.png) |
| Validasi pendaftaran<br>![](screenshots/l2m-05-daftar-validasi.png) | Pesanan dibuat, instruksi transfer<br>![](screenshots/l2m-06-pesanan-dibuat.png) |
| Upload file selain gambar/PDF ditolak<br>![](screenshots/l2m-07-upload-ditolak.png) | Bukti terkirim, menunggu verifikasi<br>![](screenshots/l2m-08-menunggu-verifikasi.png) |
| Pesanan saya<br>![](screenshots/l2m-09-pesanan-saya.png) | Dashboard admin<br>![](screenshots/l2m-10-dashboard-admin.png) |
| Filter pesanan perlu verifikasi<br>![](screenshots/l2m-11-pesanan-perlu-cek.png) | Cek bukti transfer<br>![](screenshots/l2m-12-cek-bukti.png) |
| Diverifikasi → lunas<br>![](screenshots/l2m-13-diverifikasi.png) | Pembayaran ditolak dengan alasan<br>![](screenshots/l2m-14-pembayaran-ditolak.png) |
| **Rekap produksi per ukuran**<br>![](screenshots/l2m-15-rekap-produksi.png) | Batch PO<br>![](screenshots/l2m-16-batch-po.png) |
| Tambah produk + upload foto<br>![](screenshots/l2m-17-tambah-produk.png) | Daftar produk (terpesan/kuota)<br>![](screenshots/l2m-18-daftar-produk.png) |
| Pembeli: siap diambil<br>![](screenshots/l2m-19-siap-diambil.png) | Pembeli melihat alasan penolakan & upload ulang<br>![](screenshots/l2m-20-pembeli-lihat-penolakan.png) |
| Produk dari batch yang sudah ditutup<br>![](screenshots/l2m-21-po-ditutup.png) | Database di phpMyAdmin<br>![](screenshots/l2m-22-phpmyadmin.png) |

Contoh isi CSV rekap yang diunduh (dibuka di Excel, pemisah `;`):
```
Produk;S;M;L;XL;XXL;Total
"Kaos Dies Natalis";0;2;6;1;1;10
"Hoodie Dies Natalis";1;3;0;1;0;5
"Totebag Kanvas";-;-;-;-;-;2
...
```

### Penjelasan kode penting
- **Library buatan sendiri `libraries/Keranjang_belanja.php`** — library *Cart* bawaan CI3 sudah *deprecated*, jadi dibuat versi sederhana yang menyimpan `"produk_id|ukuran" => qty` di session. Library di-autoload dengan alias `'keranjang_belanja' => 'keranjang'` sehingga dipanggil sebagai `$this->keranjang` (nama class sengaja dibedakan dari controller `Keranjang` agar tidak bentrok).
- **Checkout berlapis** (`Keranjang::checkout()`): (1) batch masih dibuka, (2) kuota produk terbatas masih cukup, (3) pesanan + item disimpan dalam **satu transaksi** (`trans_start`/`trans_complete`) di `Pesanan_model::buat()`. Kode pesanan `MHyymmdd-0001` dibuat setelah `insert_id` diketahui.
- **Bukti transfer bersifat privat** — file disimpan di `storage/bukti/` dengan nama acak (`encrypt_name`). Folder tersebut diblokir `.htaccess` (`Require all denied`, akses langsung → 403), dan file hanya dikirim lewat controller (`Pesanan::bukti()` / `admin/Pesanan::bukti()`) setelah memeriksa bahwa yang meminta adalah pemilik pesanan atau admin.
- **Rekap produksi** (`Pesanan_model::rekap_produksi()`) — `GROUP BY produk, ukuran` dari pesanan berstatus sah, lalu diubah menjadi tabel *pivot* S–XXL di PHP. CSV dibuat dengan `fputcsv()` + BOM UTF-8 dan dikirim dengan helper `download` (`force_download`).
- **Status batch tidak disimpan** — `Batch_model::status()` membandingkan waktu sekarang dengan `dibuka_at`/`ditutup_at`, sehingga tidak perlu *cron job* untuk menutup PO.
- **AdminLTE dua layout** — `layouts/toko.php` memakai varian `layout-top-nav` (tanpa sidebar, lebih cocok untuk pembeli), sedangkan `layouts/admin.php` memakai sidebar. Controller memilih layout lewat properti `$layout`.

---

## 6. Ringkasan Konsep CodeIgniter yang Dipakai

| Konsep | Tokobuah | SIAKAD | MerchHima |
|---|:-:|:-:|:-:|
| MVC: controller, model, view | ✓ | ✓ | ✓ |
| Routing custom (`config/routes.php`) | ✓ | ✓ | ✓ |
| Controller dalam subfolder (`admin/`, `dosen/`, `mahasiswa/`) | ✓ | ✓ | ✓ |
| Extend core (`MY_Controller`) | ✓ | ✓ | ✓ |
| Extend core (`MY_Model`) | | ✓ | ✓ |
| Query Builder + JOIN + subquery | ✓ | ✓ | ✓ |
| Transaksi database | | ✓ | ✓ |
| View database (SQL VIEW) | | ✓ | |
| Form Validation + callback custom | ✓ | ✓ | ✓ |
| Library Upload | ✓ | | ✓ |
| Session + flashdata | ✓ | ✓ | ✓ |
| Helper custom | ✓ | ✓ | ✓ |
| Library custom | | | ✓ |
| File bahasa (Indonesian) | ✓ | ✓ | ✓ |
| CSRF protection | ✓ | ✓ | ✓ |
| Download helper (CSV) | | | ✓ |
| Template | SB Admin 7 | AdminLTE 3 | AdminLTE 3 |

## 7. Kesimpulan

- **Latihan 1** membahas fondasi CodeIgniter 3, mulai dari konfigurasi, routing, view, template admin, CRUD dengan validasi, upload file, hingga login dan pembatasan role. Semua itu digabung dalam satu aplikasi Tokobuah.
- **Latihan 2** menerapkan fondasi tersebut pada dua studi kasus yang lebih kompleks:
  - **SIAKAD Presensi** menunjukkan pengelolaan banyak role, otorisasi data per pengguna, logika waktu (sesi berkode yang kedaluwarsa), dan perhitungan rekap memakai SQL VIEW.
  - **MerchHima** menunjukkan alur transaksi e-commerce preorder: keranjang berbasis session, checkout dalam transaksi database, kuota, verifikasi pembayaran dengan file privat, *state machine* status pesanan, dan laporan produksi yang bisa diekspor.
- Fitur seperti `MY_Controller`/`MY_Model`, *layout rendering*, library dan helper custom membuat kode lebih ringkas dan mudah dikembangkan, walaupun CodeIgniter 3 sendiri tergolong framework yang ringan.
