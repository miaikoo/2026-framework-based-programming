-- =========================================================
-- Latihan 2b - MerchHima: Preorder Merchandise Himpunan (CodeIgniter 3)
-- Database: pbkk_merchpo
-- Import lewat phpMyAdmin (tab Import) atau:
--   mysql -u root < merch_po.sql
-- Semua akun memakai password: merch123
-- =========================================================
DROP DATABASE IF EXISTS pbkk_merchpo;
CREATE DATABASE pbkk_merchpo CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE pbkk_merchpo;

CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    no_wa       VARCHAR(20)  NOT NULL,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin','pembeli') NOT NULL DEFAULT 'pembeli',
    created_at  DATETIME     NOT NULL
) ENGINE=InnoDB;

-- Satu gelombang preorder. Status (segera/dibuka/ditutup) dihitung dari tanggal.
CREATE TABLE batch_po (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama            VARCHAR(100) NOT NULL,
    deskripsi       TEXT,
    dibuka_at       DATETIME     NOT NULL,
    ditutup_at      DATETIME     NOT NULL,
    estimasi_ambil  DATE         NOT NULL,
    rekening        VARCHAR(150) NOT NULL,
    CHECK (ditutup_at > dibuka_at)
) ENGINE=InnoDB;

CREATE TABLE produk (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    batch_id      INT UNSIGNED NOT NULL,
    nama          VARCHAR(100) NOT NULL,
    deskripsi     TEXT,
    harga         INT UNSIGNED NOT NULL,
    gambar        VARCHAR(255) NOT NULL DEFAULT 'default.png',
    pakai_ukuran  TINYINT(1)   NOT NULL DEFAULT 0,       -- 1 = pembeli wajib memilih S-XXL
    kuota         INT UNSIGNED NULL,                     -- NULL = tanpa batas
    CONSTRAINT fk_produk_batch FOREIGN KEY (batch_id) REFERENCES batch_po(id)
) ENGINE=InnoDB;

CREATE TABLE pesanan (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode             VARCHAR(20)  NOT NULL UNIQUE,
    user_id          INT UNSIGNED NOT NULL,
    batch_id         INT UNSIGNED NOT NULL,
    total            INT UNSIGNED NOT NULL,
    status           ENUM('menunggu_pembayaran','menunggu_verifikasi','lunas','ditolak','siap_diambil','selesai','dibatalkan')
                     NOT NULL DEFAULT 'menunggu_pembayaran',
    catatan          VARCHAR(255) NULL,      -- dari pembeli
    bukti_bayar      VARCHAR(255) NULL,      -- nama file di folder privat
    alasan_tolak     VARCHAR(255) NULL,      -- dari admin
    created_at       DATETIME     NOT NULL,
    dibayar_at       DATETIME     NULL,
    diverifikasi_at  DATETIME     NULL,
    CONSTRAINT fk_pesanan_user  FOREIGN KEY (user_id)  REFERENCES users(id),
    CONSTRAINT fk_pesanan_batch FOREIGN KEY (batch_id) REFERENCES batch_po(id)
) ENGINE=InnoDB;

CREATE TABLE pesanan_item (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pesanan_id  INT UNSIGNED NOT NULL,
    produk_id   INT UNSIGNED NOT NULL,
    ukuran      ENUM('S','M','L','XL','XXL') NULL,
    qty         SMALLINT UNSIGNED NOT NULL,
    harga       INT UNSIGNED NOT NULL,              -- harga saat checkout
    subtotal    INT UNSIGNED NOT NULL,
    CONSTRAINT fk_item_pesanan FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    CONSTRAINT fk_item_produk  FOREIGN KEY (produk_id)  REFERENCES produk(id),
    CHECK (qty > 0)
) ENGINE=InnoDB;

-- ===================== DATA CONTOH =====================

INSERT INTO users (nama, email, no_wa, password, role, created_at) VALUES
('Admin Merch', 'admin@merchhima.test', '081200000001', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'admin', '2026-08-01 10:00:00'),
('Aditya Pratama', 'aditya@student.kampus.test', '081234560001', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Bunga Lestari', 'bunga@student.kampus.test', '081234560002', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Cahyo Nugroho', 'cahyo@student.kampus.test', '081234560003', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Dewi Anggraini', 'dewi@student.kampus.test', '081234560004', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Eko Saputra', 'eko@student.kampus.test', '081234560005', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Fitri Rahmawati', 'fitri@student.kampus.test', '081234560006', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Galih Permana', 'galih@student.kampus.test', '081234560007', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00'),
('Hana Salsabila', 'hana@student.kampus.test', '081234560008', '$2y$12$8OyuQqYkXXwSGGazs5HVGO9WrXyaC65lcORPut1q/OYWjkln1paZ6', 'pembeli', '2026-08-01 10:00:00');

INSERT INTO batch_po (nama, deskripsi, dibuka_at, ditutup_at, estimasi_ambil, rekening) VALUES
('PO Jaket Angkatan 2025', 'Jaket angkatan bordir untuk mahasiswa angkatan 2025.', '2026-08-01 08:00:00', '2026-08-20 23:59:59', '2026-09-25', 'BNI 0123456789 a.n. Bendahara Himpunan (contoh)'),
('PO Merch Dies Natalis 2026', 'Rangkaian merchandise resmi Dies Natalis himpunan ke-24. Produksi dimulai setelah PO ditutup.', '2026-11-10 08:00:00', '2026-11-30 23:59:59', '2026-12-21', 'BNI 0123456789 a.n. Bendahara Himpunan (contoh)'),
('PO Kalender Meja 2027', 'Kalender meja berisi foto kegiatan himpunan sepanjang 2026.', '2026-12-01 08:00:00', '2026-12-15 23:59:59', '2027-01-10', 'BNI 0123456789 a.n. Bendahara Himpunan (contoh)');

INSERT INTO produk (batch_id, nama, deskripsi, harga, gambar, pakai_ukuran, kuota) VALUES
(1, 'Jaket Angkatan 2025', 'Jaket parasut dengan bordir logo himpunan dan angkatan di dada kiri.', 210000, 'jaket-angkatan.png', 1, NULL),
(2, 'Kaos Dies Natalis', 'Kaos cotton combed 24s, sablon plastisol desain Dies Natalis ke-24.', 95000, 'kaos-dies.png', 1, NULL),
(2, 'Hoodie Dies Natalis', 'Hoodie fleece tebal dengan bordir di dada dan sablon besar di punggung.', 185000, 'hoodie-dies.png', 1, NULL),
(2, 'Totebag Kanvas', 'Totebag kanvas 12oz ukuran 35x40 cm, cocok untuk laptop 14 inci.', 55000, 'totebag.png', 0, NULL),
(2, 'Stiker Pack (isi 8)', 'Delapan stiker vinyl tahan air bertema maskot himpunan.', 15000, 'stiker.png', 0, NULL),
(2, 'Lanyard + ID Card Holder', 'Lanyard printing dua sisi lengkap dengan holder akrilik.', 25000, 'lanyard.png', 0, NULL),
(2, 'Pin Enamel Edisi Terbatas', 'Pin enamel emas edisi Dies Natalis, hanya diproduksi 40 buah.', 20000, 'pin.png', 0, '40'),
(3, 'Kalender Meja 2027', 'Kalender meja 13 lembar berisi foto kegiatan himpunan.', 45000, 'kalender.png', 0, NULL);

INSERT INTO pesanan (kode, user_id, batch_id, total, status, catatan, bukti_bayar, alasan_tolak, created_at, dibayar_at, diverifikasi_at) VALUES
('MH260802-0001', 2, 1, 210000, 'selesai', NULL, 'contoh-bukti-2.png', NULL, '2026-08-02 09:15:00', '2026-08-02 21:15:00', '2026-08-03 06:15:00'),
('MH260803-0002', 3, 1, 210000, 'selesai', 'Tolong dipisah per nama', 'contoh-bukti-3.png', NULL, '2026-08-03 10:15:00', '2026-08-03 15:15:00', '2026-08-04 03:15:00'),
('MH260804-0003', 4, 1, 210000, 'selesai', NULL, 'contoh-bukti-1.png', NULL, '2026-08-04 11:15:00', '2026-08-05 08:15:00', '2026-08-05 18:15:00'),
('MH260805-0004', 5, 1, 210000, 'selesai', NULL, 'contoh-bukti-2.png', NULL, '2026-08-05 12:15:00', '2026-08-06 11:15:00', '2026-08-07 06:15:00'),
('MH260806-0005', 6, 1, 210000, 'selesai', NULL, 'contoh-bukti-3.png', NULL, '2026-08-06 13:15:00', '2026-08-06 15:15:00', '2026-08-07 09:15:00'),
('MH260807-0006', 7, 1, 210000, 'selesai', 'Tolong dipisah per nama', 'contoh-bukti-1.png', NULL, '2026-08-07 14:15:00', '2026-08-07 22:15:00', '2026-08-08 17:15:00'),
('MH260808-0007', 9, 1, 210000, 'selesai', NULL, 'contoh-bukti-2.png', NULL, '2026-08-08 15:15:00', '2026-08-09 03:15:00', '2026-08-09 09:15:00'),
('MH261110-0008', 2, 2, 310000, 'lunas', NULL, 'contoh-bukti-3.png', NULL, '2026-11-10 13:45:00', '2026-11-11 08:45:00', '2026-11-11 13:45:00'),
('MH261110-0009', 3, 2, 25000, 'lunas', NULL, 'contoh-bukti-1.png', NULL, '2026-11-10 18:36:00', '2026-11-11 10:36:00', '2026-11-11 15:36:00'),
('MH261111-0010', 4, 2, 220000, 'menunggu_verifikasi', 'Tolong dipisah per nama', 'contoh-bukti-2.png', NULL, '2026-11-11 04:36:00', '2026-11-11 21:36:00', NULL),
('MH261111-0011', 5, 2, 150000, 'lunas', 'Tolong dipisah per nama', 'contoh-bukti-3.png', NULL, '2026-11-11 15:40:00', '2026-11-11 18:40:00', '2026-11-12 08:40:00'),
('MH261112-0012', 6, 2, 170000, 'menunggu_pembayaran', NULL, NULL, NULL, '2026-11-12 01:05:00', NULL, NULL),
('MH261112-0013', 7, 2, 130000, 'ditolak', NULL, 'contoh-bukti-2.png', 'Nominal transfer tidak sesuai total pesanan.', '2026-11-12 06:44:00', '2026-11-12 08:44:00', NULL),
('MH261112-0014', 9, 2, 190000, 'menunggu_verifikasi', NULL, 'contoh-bukti-3.png', NULL, '2026-11-12 15:56:00', '2026-11-13 20:56:00', NULL),
('MH261113-0015', 3, 2, 280000, 'lunas', 'Tolong dipisah per nama', 'contoh-bukti-1.png', NULL, '2026-11-13 05:32:00', '2026-11-14 07:32:00', '2026-11-14 22:32:00'),
('MH261113-0016', 5, 2, 375000, 'lunas', NULL, 'contoh-bukti-2.png', NULL, '2026-11-13 11:29:00', '2026-11-14 16:29:00', '2026-11-15 01:29:00'),
('MH261113-0017', 6, 2, 280000, 'lunas', 'Tolong dipisah per nama', 'contoh-bukti-3.png', NULL, '2026-11-13 22:13:00', '2026-11-14 21:13:00', '2026-11-15 02:13:00'),
('MH261114-0018', 4, 2, 240000, 'dibatalkan', 'Tolong dipisah per nama', NULL, NULL, '2026-11-14 04:56:00', NULL, NULL),
('MH261114-0019', 7, 2, 185000, 'lunas', 'Ambil di sekretariat ya kak', 'contoh-bukti-2.png', NULL, '2026-11-14 14:58:00', '2026-11-14 22:58:00', '2026-11-15 09:58:00'),
('MH261115-0020', 9, 2, 230000, 'lunas', 'Ambil di sekretariat ya kak', 'contoh-bukti-3.png', NULL, '2026-11-15 00:47:00', '2026-11-16 06:47:00', '2026-11-17 02:47:00'),
('MH261115-0021', 2, 2, 185000, 'menunggu_verifikasi', NULL, 'contoh-bukti-1.png', NULL, '2026-11-15 11:03:00', '2026-11-15 19:03:00', NULL);

INSERT INTO pesanan_item (pesanan_id, produk_id, ukuran, qty, harga, subtotal) VALUES
(1, 1, 'XL', 1, 210000, 210000),
(2, 1, 'M', 1, 210000, 210000),
(3, 1, 'XXL', 1, 210000, 210000),
(4, 1, 'L', 1, 210000, 210000),
(5, 1, 'XL', 1, 210000, 210000),
(6, 1, 'XL', 1, 210000, 210000),
(7, 1, 'M', 1, 210000, 210000),
(8, 2, 'M', 1, 95000, 95000),
(8, 3, 'M', 1, 185000, 185000),
(8, 5, NULL, 2, 15000, 30000),
(9, 6, NULL, 1, 25000, 25000),
(10, 2, 'XXL', 1, 95000, 95000),
(10, 4, NULL, 2, 55000, 110000),
(10, 5, NULL, 1, 15000, 15000),
(11, 2, 'XXL', 1, 95000, 95000),
(11, 4, NULL, 1, 55000, 55000),
(12, 2, 'L', 1, 95000, 95000),
(12, 7, NULL, 1, 20000, 20000),
(12, 4, NULL, 1, 55000, 55000),
(13, 4, NULL, 2, 55000, 110000),
(13, 7, NULL, 1, 20000, 20000),
(14, 2, 'XL', 2, 95000, 190000),
(15, 2, 'M', 1, 95000, 95000),
(15, 3, 'S', 1, 185000, 185000),
(16, 2, 'L', 2, 95000, 190000),
(16, 3, 'M', 1, 185000, 185000),
(17, 2, 'XL', 1, 95000, 95000),
(17, 3, 'M', 1, 185000, 185000),
(18, 2, 'L', 2, 95000, 190000),
(18, 6, NULL, 2, 25000, 50000),
(19, 3, 'XL', 1, 185000, 185000),
(20, 2, 'L', 2, 95000, 190000),
(20, 7, NULL, 2, 20000, 40000),
(21, 3, 'M', 1, 185000, 185000);
