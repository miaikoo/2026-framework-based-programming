/* =========================================================
   PRAKTIKUM 1 — Membuat database relasional StudentRegistrationDB
   Jalankan: sqlcmd -S localhost -E -C -i 01_create_database.sql
   ========================================================= */

-- Hapus database lama agar skrip bisa dijalankan ulang dari nol
IF DB_ID('StudentRegistrationDB') IS NOT NULL
BEGIN
    ALTER DATABASE StudentRegistrationDB SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE StudentRegistrationDB;
END
GO

CREATE DATABASE StudentRegistrationDB;
GO

USE StudentRegistrationDB;
GO

/* ---------- 1. Programs (Program Studi) ---------- */
CREATE TABLE Programs (
    ProgramId   INT IDENTITY(1,1) PRIMARY KEY,
    ProgramCode VARCHAR(10)   NOT NULL UNIQUE,
    ProgramName NVARCHAR(100) NOT NULL,
    Faculty     NVARCHAR(100) NOT NULL
);
GO

/* ---------- 2. Students (Mahasiswa) ---------- */
CREATE TABLE Students (
    StudentId  INT IDENTITY(1,1) PRIMARY KEY,
    Nim        VARCHAR(20)   NOT NULL UNIQUE,
    FullName   NVARCHAR(100) NOT NULL,
    Gender     CHAR(1)       NOT NULL CHECK (Gender IN ('L', 'P')),
    Email      NVARCHAR(100) NOT NULL UNIQUE,
    BirthDate  DATE          NOT NULL,
    EntryYear  SMALLINT      NOT NULL CHECK (EntryYear BETWEEN 2000 AND 2100),
    ProgramId  INT           NOT NULL,
    CONSTRAINT FK_Students_Programs
        FOREIGN KEY (ProgramId) REFERENCES Programs(ProgramId)
);
GO

/* ---------- 3. Courses (Mata Kuliah) ---------- */
CREATE TABLE Courses (
    CourseId   INT IDENTITY(1,1) PRIMARY KEY,
    CourseCode VARCHAR(10)   NOT NULL UNIQUE,
    CourseName NVARCHAR(100) NOT NULL,
    Credits    TINYINT       NOT NULL CHECK (Credits BETWEEN 1 AND 6),
    ProgramId  INT           NOT NULL,
    CONSTRAINT FK_Courses_Programs
        FOREIGN KEY (ProgramId) REFERENCES Programs(ProgramId)
);
GO

/* ---------- 4. Registrations (Pendaftaran mata kuliah) ----------
   Tabel penghubung many-to-many antara Students dan Courses */
CREATE TABLE Registrations (
    RegistrationId INT IDENTITY(1,1) PRIMARY KEY,
    StudentId      INT         NOT NULL,
    CourseId       INT         NOT NULL,
    Semester       VARCHAR(20) NOT NULL,          -- contoh: '2026/2027 Ganjil'
    RegisteredAt   DATETIME2   NOT NULL DEFAULT SYSDATETIME(),
    Status         VARCHAR(15) NOT NULL DEFAULT 'Aktif'
                   CHECK (Status IN ('Aktif', 'Batal')),
    CONSTRAINT FK_Registrations_Students
        FOREIGN KEY (StudentId) REFERENCES Students(StudentId) ON DELETE CASCADE,
    CONSTRAINT FK_Registrations_Courses
        FOREIGN KEY (CourseId) REFERENCES Courses(CourseId),
    -- Mahasiswa tidak boleh mendaftar mata kuliah yang sama dua kali di semester yang sama
    CONSTRAINT UQ_Registrations UNIQUE (StudentId, CourseId, Semester)
);
GO

/* ---------- 5. View gabungan untuk aplikasi ---------- */
CREATE VIEW vw_RegistrationDetails AS
SELECT  r.RegistrationId,
        s.Nim,
        s.FullName,
        p.ProgramName,
        c.CourseCode,
        c.CourseName,
        c.Credits,
        r.Semester,
        r.Status,
        r.RegisteredAt
FROM Registrations r
JOIN Students s ON s.StudentId = r.StudentId
JOIN Programs p ON p.ProgramId = s.ProgramId
JOIN Courses  c ON c.CourseId  = r.CourseId;
GO

PRINT 'Database StudentRegistrationDB dan 4 tabel berhasil dibuat.';
GO
