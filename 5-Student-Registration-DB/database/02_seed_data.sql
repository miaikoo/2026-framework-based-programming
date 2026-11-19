/* =========================================================
   PRAKTIKUM 1 — Mengisi data contoh
   Jalankan setelah 01_create_database.sql
   ========================================================= */
USE StudentRegistrationDB;
GO

INSERT INTO Programs (ProgramCode, ProgramName, Faculty) VALUES
('IF', N'Teknik Informatika',       N'Fakultas Teknologi Elektro dan Informatika Cerdas'),
('SI', N'Sistem Informasi',         N'Fakultas Teknologi Elektro dan Informatika Cerdas'),
('TI', N'Teknologi Informasi',      N'Fakultas Teknologi Elektro dan Informatika Cerdas'),
('SD', N'Sains Data',               N'Fakultas Sains dan Analitika Data');
GO

INSERT INTO Students (Nim, FullName, Gender, Email, BirthDate, EntryYear, ProgramId) VALUES
('5025241001', N'Budi Santoso',     'L', 'budi.santoso@student.ac.id',   '2006-03-15', 2024, 1),
('5025241002', N'Dewi Lestari',     'P', 'dewi.lestari@student.ac.id',   '2006-07-21', 2024, 1),
('5025241003', N'Rizky Maulana',    'L', 'rizky.maulana@student.ac.id',  '2005-11-02', 2024, 1),
('5026241004', N'Siti Aminah',      'P', 'siti.aminah@student.ac.id',    '2006-01-09', 2024, 2),
('5026241005', N'Fajar Nugroho',    'L', 'fajar.nugroho@student.ac.id',  '2005-09-30', 2024, 2),
('5027241006', N'Putri Ramadhani',  'P', 'putri.ramadhani@student.ac.id','2006-05-12', 2024, 3),
('5027241007', N'Andi Pratama',     'L', 'andi.pratama@student.ac.id',   '2006-02-25', 2024, 3),
('5028241008', N'Nadia Kusuma',     'P', 'nadia.kusuma@student.ac.id',   '2006-08-18', 2024, 4);
GO

INSERT INTO Courses (CourseCode, CourseName, Credits, ProgramId) VALUES
('IF2101', N'Pemrograman Berbasis Kerangka Kerja', 3, 1),
('IF2102', N'Basis Data',                          3, 1),
('IF2103', N'Struktur Data',                       4, 1),
('SI2101', N'Analisis Proses Bisnis',              3, 2),
('SI2102', N'Sistem Enterprise',                   3, 2),
('TI2101', N'Jaringan Komputer',                   3, 3),
('SD2101', N'Statistika Data',                     3, 4),
('SD2102', N'Pembelajaran Mesin',                  4, 4);
GO

-- Mahasiswa boleh mengambil mata kuliah lintas prodi (contoh: SI mengambil Basis Data)
INSERT INTO Registrations (StudentId, CourseId, Semester, RegisteredAt, Status) VALUES
(1, 1, '2026/2027 Ganjil', '2026-08-18 08:15', 'Aktif'),
(1, 2, '2026/2027 Ganjil', '2026-08-18 08:17', 'Aktif'),
(1, 3, '2026/2027 Ganjil', '2026-08-18 08:20', 'Aktif'),
(2, 1, '2026/2027 Ganjil', '2026-08-18 09:02', 'Aktif'),
(2, 2, '2026/2027 Ganjil', '2026-08-18 09:05', 'Aktif'),
(3, 1, '2026/2027 Ganjil', '2026-08-19 10:11', 'Aktif'),
(3, 3, '2026/2027 Ganjil', '2026-08-19 10:14', 'Batal'),
(4, 4, '2026/2027 Ganjil', '2026-08-19 13:30', 'Aktif'),
(4, 5, '2026/2027 Ganjil', '2026-08-19 13:32', 'Aktif'),
(4, 2, '2026/2027 Ganjil', '2026-08-19 13:35', 'Aktif'),
(5, 4, '2026/2027 Ganjil', '2026-08-20 07:45', 'Aktif'),
(6, 6, '2026/2027 Ganjil', '2026-08-20 11:00', 'Aktif'),
(6, 1, '2026/2027 Ganjil', '2026-08-20 11:04', 'Aktif'),
(7, 6, '2026/2027 Ganjil', '2026-08-21 14:20', 'Aktif'),
(8, 7, '2026/2027 Ganjil', '2026-08-21 15:10', 'Aktif'),
(8, 8, '2026/2027 Ganjil', '2026-08-21 15:12', 'Aktif');
GO

PRINT 'Data contoh berhasil dimasukkan.';
GO
