-- =========================================================
-- Tugas 9 - ShopEase Authentication System (CodeIgniter 3)
-- Database: db_shopease
-- Import lewat phpMyAdmin (tab Import) atau:  mysql -u root < db_shopease.sql
-- Password semua akun contoh: shopease123
-- =========================================================
DROP DATABASE IF EXISTS db_shopease;
CREATE DATABASE db_shopease CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_shopease;

-- Pengguna. Peran: customer (pembeli), operator (staf toko), admin (pemilik/pengelola).
CREATE TABLE users (
    id_user        INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,                 -- hasil password_hash()
    role           ENUM('customer','admin','operator') NOT NULL DEFAULT 'customer',
    phone          VARCHAR(20),
    address        TEXT,
    last_login_at  DATETIME NULL,
    created_at     DATETIME NULL,
    updated_at     DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Setiap percobaan login dicatat: dipakai untuk membatasi percobaan gagal
-- dan ditampilkan sebagai log aktivitas di dashboard admin.
CREATE TABLE login_attempts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(100) NOT NULL,
    ip_address  VARCHAR(45)  NOT NULL,
    berhasil    TINYINT(1)   NOT NULL,
    user_agent  VARCHAR(255) NULL,
    created_at  DATETIME     NOT NULL,
    INDEX idx_email_ip_waktu (email, ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Token "Ingat saya" (pola selector + validator).
-- Cookie berisi selector:validator; yang disimpan di database hanya hash validator.
CREATE TABLE remember_tokens (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_user         INT          NOT NULL,
    selector        CHAR(24)     NOT NULL UNIQUE,
    validator_hash  CHAR(64)     NOT NULL,
    expires_at      DATETIME     NOT NULL,
    created_at      DATETIME     NOT NULL,
    CONSTRAINT fk_token_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- Akun contoh ----------
INSERT INTO users (name, email, password, role, phone, address, created_at, updated_at) VALUES
('Sri Rahayu',      'admin@shopease.test',    '$2y$12$eeG92llF.CBIUZepJvdiNOlwOtBcxxhLJYVQDuInpT1zpRqo7TeRu', 'admin',    '081200000001', 'Bantul, DI Yogyakarta',     '2026-09-01 08:00:00', '2026-09-01 08:00:00'),
('Bayu Operator',   'operator@shopease.test', '$2y$12$eeG92llF.CBIUZepJvdiNOlwOtBcxxhLJYVQDuInpT1zpRqo7TeRu', 'operator', '081200000002', 'Bantul, DI Yogyakarta',     '2026-09-01 08:05:00', '2026-09-01 08:05:00'),
('Raka Aditama',    'raka@shopease.test',     '$2y$12$eeG92llF.CBIUZepJvdiNOlwOtBcxxhLJYVQDuInpT1zpRqo7TeRu', 'customer', '081234567801', 'Jl. Pandanaran 12, Semarang', '2026-10-03 19:20:00', '2026-10-03 19:20:00'),
('Laras Wening',    'laras@shopease.test',    '$2y$12$eeG92llF.CBIUZepJvdiNOlwOtBcxxhLJYVQDuInpT1zpRqo7TeRu', 'customer', '081234567802', 'Jl. Dago 45, Bandung',     '2026-10-21 10:11:00', '2026-10-21 10:11:00'),
('Yosef Hartono',   'yosef@shopease.test',    '$2y$12$eeG92llF.CBIUZepJvdiNOlwOtBcxxhLJYVQDuInpT1zpRqo7TeRu', 'customer', NULL,           NULL,                       '2026-11-09 21:40:00', '2026-11-09 21:40:00');
