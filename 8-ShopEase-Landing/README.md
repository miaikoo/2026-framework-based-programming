# Tugas 8 PBKK — Landing Page E-Commerce ShopEase (CodeIgniter 3)

Tugas ini membuat **landing page toko online ShopEase** dengan **CodeIgniter 3.1.13**, dengan memodifikasi tiga bagian utama framework:

- **Routing** — URL custom di `config/routes.php` (kategori, pencarian, detail produk, keranjang, halaman statis, dan halaman 404 sendiri).
- **Controller** — `Home`, `Produk`, `Keranjang`, `Halaman` menyiapkan data lalu mengirimnya ke view.
- **View** — satu layout utama, partial yang dipakai ulang (navbar, footer, kartu produk, label harga), dan landing page yang dipecah per section.

ShopEase mengikuti studi kasus jurnal *"Website E-Commerce Menggunakan Model View Controller (MVC) dengan Framework CodeIgniter — Studi Kasus: Toko Miniatur"* (Jurnal Ilmiah DASI Vol. 16 No. 1, 2015): toko miniatur di Yogyakarta yang beralih dari penjualan konvensional ke toko online.

> Versi siap-tempel untuk Blogspot ada di [`blogspot.html`](blogspot.html) — Blogger → New Post → ikon pensil → **HTML view** → paste seluruh isinya.

![Landing page ShopEase](screenshots/s8-02-hero.png)

## Dari studi kasus ke landing page

| Isi jurnal | Diterapkan di ShopEase |
|---|---|
| Toko miniatur di Bantul, Yogyakarta yang masih berjualan offline | Katalog 14 miniatur (kereta, kendaraan darat, pesawat, kapal, kendaraan tradisional), dikirim dari Bantul |
| Analisis PIECES: transaksi 24 jam, pembeli tidak perlu datang ke toko | Poin di hero: pesanan diterima 24 jam, konfirmasi bayar online, kirim ke seluruh Indonesia |
| Informasi diskon periode tertentu langsung tampil *real-time* | Section **promo** dengan hitung mundur; harga diskon otomatis terhitung di semua halaman selama periode promo |
| Fitur *shopping cart* | Keranjang belanja berbasis session, jumlah barang tampil di navbar |
| Controller `produk_terbaru` & `produk_terlaris` | Section **Baru masuk etalase** dan **Paling banyak dibeli** |
| Alur halaman selesai bayar: keranjang → jasa pengiriman → metode pembayaran → konfirmasi → selesai | Section **Dari keranjang sampai rumah** (5 tahap) |
| Data kurir, bank, CS, testimoni produk | Section layanan (kurir, bank, customer service) dan **Kata pelanggan** |
| Pengguna: pengunjung, pelanggan, administrator | Dijelaskan di halaman **Tentang** |
| `base_url` dihitung otomatis dari `$_SERVER` | Dipakai juga di `config/config.php` |

## Cara menjalankan

1. Start **Apache** di XAMPP (database tidak diperlukan; data katalog ada di model).
2. Letakkan folder di `htdocs`, misalnya dengan *junction*:
   ```powershell
   New-Item -ItemType Junction -Path "C:\xampp\htdocs\pbkk8" -Target "D:\...\8-ShopEase-Landing"
   ```
3. Buka **http://localhost/pbkk8/shopease/**

> CodeIgniter 3.1.13 dirilis sebelum PHP 8.2, sehingga di PHP 8.5 muncul pesan *Deprecated*. Pesan itu hanya peringatan dan disembunyikan lewat `error_reporting()` di `index.php`.

## Struktur folder

```
8-ShopEase-Landing/
├── README.md, blogspot.html, screenshots/
└── shopease/                      ← aplikasi CodeIgniter 3
    ├── index.php, .htaccess
    ├── assets/css/shopease.css    ← identitas visual
    ├── assets/img/produk/         ← foto produk
    ├── system/
    └── application/
        ├── config/routes.php      ← ROUTING
        ├── core/MY_Controller.php ← data global + render() layout + halaman 404
        ├── controllers/           ← CONTROLLER
        │   ├── Home.php           (landing, kategori, cari, newsletter)
        │   ├── Produk.php         (detail produk)
        │   ├── Keranjang.php      (lihat, tambah, hapus)
        │   └── Halaman.php        (tentang, cara belanja, kontak, 404)
        ├── models/Katalog_model.php
        ├── helpers/shopease_helper.php
        └── views/                 ← VIEW
            ├── layouts/main.php
            ├── partials/          (navbar, footer, kartu_produk, tag_harga)
            ├── home/landing.php + home/sections/*  (hero, kategori, promo, terbaru,
            │                                         terlaris, cara_belanja, testimoni, layanan)
            ├── home/daftar.php, produk/detail.php, keranjang/index.php
            └── halaman/           (tentang, cara_belanja, kontak, tidak_ditemukan)
```

## 1. Routing (`application/config/routes.php`)

```php
$route['default_controller'] = 'home';

$route['kategori/(:any)'] = 'home/kategori/$1';
$route['cari']            = 'home/cari';
$route['produk/(:any)']   = 'produk/detail/$1';
$route['langganan']       = 'home/langganan';

$route['keranjang']        = 'keranjang/index';
$route['keranjang/tambah'] = 'keranjang/tambah';
$route['keranjang/hapus']  = 'keranjang/hapus';

$route['(tentang|cara-belanja|kontak)'] = 'halaman/tampil/$1';
$route['404_override'] = 'halaman/tidak_ditemukan';
```

| URL | Diarahkan ke | Keterangan |
|---|---|---|
| `/` | `Home::index()` | Landing page |
| `/kategori/kereta` | `Home::kategori('kereta')` | Wildcard `(:any)` diteruskan sebagai parameter |
| `/cari?q=kapal` | `Home::cari()` | Kata kunci dibaca dari query string |
| `/produk/kapal-pinisi` | `Produk::detail('kapal-pinisi')` | URL ramah SEO memakai slug, bukan ID |
| `/langganan` | `Home::langganan()` | Hanya menerima POST |
| `/tentang`, `/cara-belanja`, `/kontak` | `Halaman::tampil($1)` | **Satu route regex** untuk tiga URL |
| URL lain | `Halaman::tidak_ditemukan()` | `404_override` menampilkan 404 bergaya ShopEase |

## 2. Controller

- **`core/MY_Controller.php`** — semua controller mewarisi class ini. Konstruktornya mengirim data yang tampil di setiap halaman (menu kategori, jumlah isi keranjang, info layanan) lewat `$this->load->vars()`. Method `render($view, $data)` me-render view isi ke string lalu menyisipkannya ke `layouts/main.php`. Method `tidak_ada()` menampilkan halaman 404 yang sama, karena `show_404()` bawaan tidak melewati `404_override`.
- **`Home`** — `index()` mengumpulkan semua data landing page (produk unggulan, terbaru, terlaris, promo aktif, langkah belanja, testimoni). `kategori($slug)` mendukung pengurutan `?urut=terbaru|terlaris|murah|mahal`. `langganan()` memvalidasi email dengan Form Validation dan memberi pesan lewat flashdata.
- **`Produk::detail($slug)`** — produk tidak ditemukan menghasilkan 404.
- **`Keranjang`** — menyimpan `slug => jumlah` di session. `tambah()` membatasi jumlah sesuai stok (maks. 10). Bila dikirim dari kartu produk, pembeli dikembalikan ke halaman asal dengan notifikasi; dari halaman detail, pembeli diarahkan ke keranjang.
- **`Halaman::tampil($slug)`** — memilih view berdasarkan slug dari route.

Data katalog ada di **`models/Katalog_model.php`** dalam bentuk array, sehingga tugas ini tidak membutuhkan database. Controller dan view hanya memanggil method model (`terbaru()`, `terlaris()`, `promo_aktif()`, `by_slug()`, ...), jadi bila data dipindah ke MySQL cukup isi method model yang diganti.

## 3. View

- **`layouts/main.php`** — kerangka HTML, font, Bootstrap 5.3, navbar, footer, notifikasi keranjang, dan script hitung mundur promo.
- **`home/landing.php`** — hanya merangkai section: `hero → kategori → promo → terbaru → terlaris → cara_belanja → testimoni → layanan`. Urutan section mudah diubah, dan section promo otomatis hilang ketika tidak ada promo aktif.
- **Partial yang dipakai ulang**: `kartu_produk.php` (landing, kategori, pencarian, produk serupa, 404) dan `tag_harga.php` (label harga gantung, otomatis berwarna merah dengan harga coret saat diskon).
- Semua output memakai `html_escape()`, dan semua form POST memakai `form_open()` sehingga membawa token CSRF.

### Desain
- Konsep **etalase toko miniatur**: hero berupa *vitrin* (lemari pajangan) berisi produk unggulan, dan **label harga gantung** ala toko hobi sebagai elemen khas di seluruh halaman.
- Palet dari dunia model kit: biru tua enamel `#1f2a44`, abu kebiruan `#eef2f6`, kuning sinyal kereta `#f2b705` (promo), merah rel `#c8102e` (diskon).
- Tipografi: **Bricolage Grotesque** untuk judul, **Public Sans** untuk teks.
- Alur belanja digambar sebagai **jalur rel dengan lima stasiun**; peringkat terlaris memakai nomor karena memang berupa urutan.
- Responsif sampai lebar ponsel, fokus keyboard terlihat, dan animasi dimatikan bila pengguna memilih *reduced motion*.

## Hasil

| | |
|---|---|
| Landing page (satu halaman penuh)<br>![](screenshots/s8-01-landing-full.png) | Tampilan mobile<br>![](screenshots/s8-17-mobile-landing.png) |
| Hero dengan vitrin produk<br>![](screenshots/s8-02-hero.png) | Promo periode + hitung mundur<br>![](screenshots/s8-03-promo-diskon.png) |
| Tambah dari kartu produk → notifikasi<br>![](screenshots/s8-04-notif-keranjang.png) | `/kategori/kereta` (harga diskon)<br>![](screenshots/s8-05-kategori-kereta.png) |
| `/kategori/darat?urut=murah`<br>![](screenshots/s8-06-kategori-urut-murah.png) | `/cari?q=kapal`<br>![](screenshots/s8-07-cari-kapal.png) |
| Pencarian tanpa hasil<br>![](screenshots/s8-08-cari-kosong.png) | `/produk/kapal-pinisi`<br>![](screenshots/s8-09-detail-produk.png) |
| `/keranjang` (total & hemat promo)<br>![](screenshots/s8-10-keranjang.png) | Validasi newsletter<br>![](screenshots/s8-11-newsletter-gagal.png) |
| Newsletter berhasil<br>![](screenshots/s8-12-newsletter-berhasil.png) | `/tentang`<br>![](screenshots/s8-13-tentang.png) |
| `/cara-belanja`<br>![](screenshots/s8-14-cara-belanja.png) | `/kontak`<br>![](screenshots/s8-15-kontak.png) |
| URL tidak dikenal → 404 ShopEase<br>![](screenshots/s8-16-halaman-404.png) | Menu mobile<br>![](screenshots/s8-18-mobile-menu.png) |

## Kesimpulan

Dengan memodifikasi **routing**, URL ShopEase menjadi ramah pengguna (`/produk/kapal-pinisi`, `/kategori/kereta`, `/cara-belanja`). Satu route regex bisa melayani beberapa halaman, dan `404_override` memberi halaman 404 sendiri. **Controller** bertugas menyiapkan data, sedangkan `MY_Controller` mengurus data global dan layout sehingga controller lain tetap ringkas. **View** dipecah menjadi layout, partial, dan section, sehingga komponen seperti kartu produk dan label harga cukup ditulis sekali dan dipakai di banyak halaman. Pemisahan ini sesuai dengan tujuan penerapan MVC pada studi kasus: aplikasi lebih mudah dipahami dan dikembangkan oleh programmer berikutnya.
