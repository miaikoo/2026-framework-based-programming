# Pemrograman Berbasis Kerangka Kerja (PBKK) 2026

Kumpulan tugas mata kuliah **Pemrograman Berbasis Kerangka Kerja**. Setiap tugas ada di foldernya sendiri dan berisi source code, `README.md` (laporan lengkap), `blogspot.html` (versi siap-tempel untuk Blogger), dan folder `screenshots/` berisi hasil asli aplikasi.

## Daftar tugas

| No | Tugas | Teknologi | Isi singkat |
|:-:|---|---|---|
| 1 | [Instalasi .NET & Data Mahasiswa](1-Instalasi-DotNet-DataMahasiswa) | C# .NET 8, Console | Instalasi .NET SDK dan aplikasi konsol untuk menambah, menampilkan, mencari, dan menghapus data mahasiswa |
| 2 | [Aplikasi Kalkulator](2-Aplikasi-Kalkulator) | C# .NET 8, Console | Kalkulator dengan operasi dasar, pangkat, akar, riwayat, dan penanganan input tidak valid |
| 3 | [Registrasi Mahasiswa WPF](3-Registrasi-Mahasiswa-WPF) | C# .NET 8, WPF | Form registrasi dengan validasi dan tabel data mahasiswa |
| 4 | [Student Management MVVM](4-Student-Management-MVVM) | WPF, MVVM, SQL Server, ADO.NET | CRUD mahasiswa dengan pola MVVM, pencarian, dan statistik |
| 5 | [Student Registration DB](5-Student-Registration-DB) | SQL Server, WPF, ADO.NET | Database relasional (PK, FK, constraint, view) dan aplikasi WPF yang membacanya ke DataGrid |
| 6 | [SITA — Sistem Informasi Tugas Akhir](https://github.com/miaikoo/sistem-informasi-tugas-akhir) ↗ | C# .NET 8, WPF, SQLite | Tugas kelompok sekaligus ETS (repository terpisah): aplikasi desktop untuk memantau tugas akhir mahasiswa, yaitu pengajuan judul, log bimbingan, jadwal sidang, dan dashboard progres |
| 7 | [CodeIgniter 3](7-CodeIgniter) | PHP, CodeIgniter 3, MySQL | **Tokobuah** (dasar CI3: routing, template, CRUD, upload, login), **SIAKAD Presensi** (presensi berkode & kelayakan UAS), dan **MerchHima** (toko preorder merchandise) |
| 8 | [ShopEase Landing Page](8-ShopEase-Landing) | PHP, CodeIgniter 3 | Landing page toko miniatur ShopEase dengan modifikasi routing, controller, dan view; studi kasus jurnal e-commerce MVC |
| 9 | [ShopEase Authentication](9-ShopEase-Auth) | PHP, CodeIgniter 3, MySQL | Registrasi, login, logout, hak akses per peran (customer, operator, admin) lewat hook, Ingat saya, dan batas percobaan login |

## Kebutuhan

| Tugas | Yang perlu dipasang |
|---|---|
| 1–3 | [.NET 8 SDK](https://dotnet.microsoft.com/download) |
| 4–5 | .NET 8 SDK + SQL Server (Express/Developer) |
| 6 | .NET 8 SDK (database SQLite dibuat otomatis); lihat README di repository-nya |
| 7–9 | [XAMPP](https://www.apachefriends.org/) (Apache, MySQL/MariaDB, PHP). CodeIgniter 3.1.13 sudah disertakan di masing-masing folder |

## Cara menjalankan

**Tugas 1–5 (C#)**
```bash
cd <folder-tugas>/<nama-proyek>
dotnet run
```
Tugas 4 dan 5 membutuhkan SQL Server yang berjalan di `localhost`. Langkah lengkap ada di README masing-masing tugas.

**Tugas 7–9 (CodeIgniter 3)**
1. Start Apache dan MySQL di XAMPP.
2. Letakkan folder tugas di `htdocs` (disalin atau dibuat *junction*), misalnya:
   ```powershell
   New-Item -ItemType Junction -Path "C:\xampp\htdocs\pbkk9" -Target "D:\...\9-ShopEase-Auth"
   ```
3. Import file `.sql` di folder `database/` lewat phpMyAdmin (Tugas 7 dan 9; Tugas 8 tidak memakai database).
4. Buka di browser, misalnya `http://localhost/pbkk9/shopease/`. Alamat dan akun contoh tiap aplikasi ada di README masing-masing tugas.

## Blogspot

Setiap folder punya `blogspot.html`. Cara memakainya: Blogger → **New Post** → ikon pensil → **HTML view** → tempel seluruh isi file. Gambar diunggah manual di tempat yang bertanda *[Sisipkan gambar: ...]*, memakai file dari folder `screenshots/`.
