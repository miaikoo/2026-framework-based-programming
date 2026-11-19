/* =========================================================
   PRAKTIKUM 1 — Query pengujian relasi
   ========================================================= */
USE StudentRegistrationDB;
GO

-- 1. Daftar mahasiswa beserta nama program studinya (JOIN 2 tabel)
SELECT s.Nim, s.FullName, s.Gender, p.ProgramName, s.EntryYear
FROM Students s
JOIN Programs p ON p.ProgramId = s.ProgramId
ORDER BY s.Nim;
GO

-- 2. Detail pendaftaran mata kuliah (JOIN 4 tabel lewat view)
SELECT Nim, FullName, CourseCode, CourseName, Credits, Status
FROM vw_RegistrationDetails
ORDER BY Nim, CourseCode;
GO

-- 3. Total SKS aktif per mahasiswa (GROUP BY + SUM)
SELECT s.Nim, s.FullName, COUNT(*) AS JumlahMK, SUM(c.Credits) AS TotalSKS
FROM Registrations r
JOIN Students s ON s.StudentId = r.StudentId
JOIN Courses  c ON c.CourseId  = r.CourseId
WHERE r.Status = 'Aktif'
GROUP BY s.Nim, s.FullName
ORDER BY TotalSKS DESC, s.Nim;
GO

-- 4. Jumlah peserta per mata kuliah, termasuk yang belum ada peserta (LEFT JOIN)
SELECT c.CourseCode, c.CourseName,
       COUNT(r.RegistrationId) AS JumlahPeserta
FROM Courses c
LEFT JOIN Registrations r ON r.CourseId = c.CourseId AND r.Status = 'Aktif'
GROUP BY c.CourseCode, c.CourseName
ORDER BY JumlahPeserta DESC, c.CourseCode;
GO

-- 5. Uji constraint: mendaftar mata kuliah yang sama dua kali harus DITOLAK
BEGIN TRY
    INSERT INTO Registrations (StudentId, CourseId, Semester) VALUES (1, 1, '2026/2027 Ganjil');
END TRY
BEGIN CATCH
    PRINT 'Ditolak (sesuai harapan): ' + ERROR_MESSAGE();
END CATCH
GO

-- 6. Uji foreign key: ProgramId yang tidak ada harus DITOLAK
BEGIN TRY
    INSERT INTO Students (Nim, FullName, Gender, Email, BirthDate, EntryYear, ProgramId)
    VALUES ('9999999999', N'Uji FK', 'L', 'uji@x.id', '2006-01-01', 2024, 99);
END TRY
BEGIN CATCH
    PRINT 'Ditolak (sesuai harapan): ' + ERROR_MESSAGE();
END CATCH
GO
