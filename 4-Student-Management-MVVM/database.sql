-- Skrip database Student Management System
-- (Opsional: aplikasi juga membuat database & tabel ini otomatis saat pertama dijalankan)

IF DB_ID('StudentDB') IS NULL
    CREATE DATABASE StudentDB;
GO

USE StudentDB;
GO

IF OBJECT_ID('dbo.Students', 'U') IS NULL
CREATE TABLE dbo.Students (
    Id      INT IDENTITY(1,1) PRIMARY KEY,
    Nim     VARCHAR(20)   NOT NULL UNIQUE,
    Nama    NVARCHAR(100) NOT NULL,
    Jurusan NVARCHAR(100) NOT NULL,
    Gender  NVARCHAR(20)  NOT NULL,
    Email   NVARCHAR(100) NOT NULL
);
GO

-- Data contoh (opsional)
INSERT INTO dbo.Students (Nim, Nama, Jurusan, Gender, Email) VALUES
('5025231001', N'Budi Santoso',  N'Teknik Informatika', N'Laki-laki', 'budi.santoso@email.com'),
('5026231002', N'Siti Aminah',   N'Sistem Informasi',   N'Perempuan', 'siti.aminah@email.com'),
('5027231003', N'Andi Pratama',  N'Sains Data',         N'Laki-laki', 'andi.pratama@email.com');
GO
