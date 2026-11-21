-- =========================================================
-- Latihan 1 - Tokobuah (CodeIgniter 3)
-- Database: pbkk_tokobuah
-- Import lewat phpMyAdmin (tab Import) atau:
--   mysql -u root < tokobuah.sql
-- =========================================================
DROP DATABASE IF EXISTS pbkk_tokobuah;
CREATE DATABASE pbkk_tokobuah CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE pbkk_tokobuah;

-- ---------- Produk (buah) ----------
CREATE TABLE products (
    product_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100)  NOT NULL,
    category     ENUM('Lokal','Impor') NOT NULL DEFAULT 'Lokal',
    price        INT UNSIGNED  NOT NULL,              -- harga per satuan, rupiah
    unit         VARCHAR(20)   NOT NULL DEFAULT 'kg', -- kg / buah / sisir / pack
    stock        INT UNSIGNED  NOT NULL DEFAULT 0,
    image        VARCHAR(255)  NOT NULL DEFAULT 'default.png',
    description  TEXT,
    created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME      NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------- Pengguna admin ----------
CREATE TABLE users (
    user_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,                -- hasil password_hash()
    full_name   VARCHAR(100) NOT NULL,
    role        ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    last_login  DATETIME     NULL,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------- Data contoh ----------
INSERT INTO products (name, category, price, unit, stock, image, description) VALUES
('Apel Malang',        'Lokal', 32000, 'kg',    40, 'apel-malang.png',   'Apel hijau dari Batu, Malang. Renyah dengan rasa manis-asam yang segar.'),
('Jeruk Pontianak',    'Lokal', 28000, 'kg',    55, 'jeruk-pontianak.png','Jeruk siam berkulit tipis, banyak air, cocok dijadikan jus.'),
('Pisang Cavendish',   'Impor', 24000, 'sisir', 30, 'pisang.png',        'Pisang matang pohon, kulit kuning mulus dan daging lembut.'),
('Anggur Merah',       'Impor', 65000, 'kg',    18, 'anggur.png',        'Anggur merah tanpa biji, manis dan cocok untuk camilan.'),
('Semangka Inul',      'Lokal', 15000, 'kg',    25, 'semangka.png',      'Semangka merah tanpa biji dengan daging buah yang padat.'),
('Stroberi Ciwidey',   'Lokal', 45000, 'pack',  20, 'stroberi.png',      'Stroberi segar petik langsung dari kebun Ciwidey, Bandung. 250 gram per pack.'),
('Nanas Subang',       'Lokal', 12000, 'buah',  35, 'nanas.png',         'Nanas madu dari Subang, manis dan tidak membuat gatal di lidah.'),
('Mangga Harum Manis', 'Lokal', 30000, 'kg',    22, 'mangga.png',        'Mangga arumanis Probolinggo, beraroma harum dan berserat halus.');

-- Password kedua akun: tokobuah123 (dibuat dengan password_hash(..., PASSWORD_DEFAULT))
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@tokobuah.test', '$2y$12$axxrs3ymdK6hXRtAKBWXcOzhI5N5YSt0zVqp42t3W1Qo8uVy79xk2', 'Admin Tokobuah', 'admin'),
('kasir', 'kasir@tokobuah.test', '$2y$12$axxrs3ymdK6hXRtAKBWXcOzhI5N5YSt0zVqp42t3W1Qo8uVy79xk2', 'Kasir Tokobuah', 'staff');
