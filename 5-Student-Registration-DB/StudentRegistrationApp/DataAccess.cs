using System.Data;
using Microsoft.Data.SqlClient;

namespace StudentRegistrationApp
{
    // Semua pembacaan data dari StudentRegistrationDB menggunakan ADO.NET
    public static class DataAccess
    {
        // Ganti "localhost" jika nama server berbeda, misal "localhost\SQLEXPRESS"
        public const string ConnectionString =
            "Server=localhost;Database=StudentRegistrationDB;Trusted_Connection=True;TrustServerCertificate=True;";

        // Query untuk setiap tampilan. {0} = filter prodi, {1} = filter kata kunci
        private static readonly Dictionary<string, string> Queries = new Dictionary<string, string>
        {
            ["Mahasiswa"] = @"
                SELECT s.Nim AS [NIM], s.FullName AS [Nama],
                       CASE s.Gender WHEN 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS [Gender],
                       p.ProgramName AS [Program Studi], s.Email,
                       FORMAT(s.BirthDate, 'dd/MM/yyyy') AS [Tgl Lahir], s.EntryYear AS [Angkatan]
                FROM Students s
                JOIN Programs p ON p.ProgramId = s.ProgramId
                WHERE (@ProgramId = 0 OR s.ProgramId = @ProgramId)
                  AND (s.Nim LIKE @Cari OR s.FullName LIKE @Cari OR s.Email LIKE @Cari)
                ORDER BY s.Nim",

            ["Registrasi Mata Kuliah"] = @"
                SELECT v.Nim AS [NIM], v.FullName AS [Nama], v.ProgramName AS [Program Studi],
                       v.CourseCode AS [Kode MK], v.CourseName AS [Mata Kuliah], v.Credits AS [SKS],
                       v.Semester, v.Status, FORMAT(v.RegisteredAt, 'dd/MM/yyyy HH:mm') AS [Waktu Daftar]
                FROM vw_RegistrationDetails v
                JOIN Students s ON s.Nim = v.Nim
                WHERE (@ProgramId = 0 OR s.ProgramId = @ProgramId)
                  AND (v.Nim LIKE @Cari OR v.FullName LIKE @Cari OR v.CourseName LIKE @Cari OR v.CourseCode LIKE @Cari)
                ORDER BY v.Nim, v.CourseCode",

            ["Total SKS per Mahasiswa"] = @"
                SELECT s.Nim AS [NIM], s.FullName AS [Nama], p.ProgramName AS [Program Studi],
                       COUNT(r.RegistrationId) AS [Jumlah MK], ISNULL(SUM(c.Credits), 0) AS [Total SKS]
                FROM Students s
                JOIN Programs p ON p.ProgramId = s.ProgramId
                LEFT JOIN Registrations r ON r.StudentId = s.StudentId AND r.Status = 'Aktif'
                LEFT JOIN Courses c ON c.CourseId = r.CourseId
                WHERE (@ProgramId = 0 OR s.ProgramId = @ProgramId)
                  AND (s.Nim LIKE @Cari OR s.FullName LIKE @Cari)
                GROUP BY s.Nim, s.FullName, p.ProgramName
                ORDER BY [Total SKS] DESC, s.Nim",

            ["Mata Kuliah"] = @"
                SELECT c.CourseCode AS [Kode MK], c.CourseName AS [Mata Kuliah], c.Credits AS [SKS],
                       p.ProgramName AS [Program Studi], COUNT(r.RegistrationId) AS [Jumlah Peserta]
                FROM Courses c
                JOIN Programs p ON p.ProgramId = c.ProgramId
                LEFT JOIN Registrations r ON r.CourseId = c.CourseId AND r.Status = 'Aktif'
                WHERE (@ProgramId = 0 OR c.ProgramId = @ProgramId)
                  AND (c.CourseCode LIKE @Cari OR c.CourseName LIKE @Cari)
                GROUP BY c.CourseCode, c.CourseName, c.Credits, p.ProgramName
                ORDER BY c.CourseCode"
        };

        public static IEnumerable<string> DaftarTampilan => Queries.Keys;

        // Mengisi DataTable lewat SqlDataAdapter -> langsung bisa dipakai sebagai sumber DataGrid
        public static DataTable Ambil(string tampilan, int programId, string kataKunci)
        {
            var tabel = new DataTable();

            using (var conn = new SqlConnection(ConnectionString))
            using (var cmd = new SqlCommand(Queries[tampilan], conn))
            {
                cmd.Parameters.AddWithValue("@ProgramId", programId);
                cmd.Parameters.AddWithValue("@Cari", "%" + (kataKunci ?? "").Trim() + "%");

                using (var adapter = new SqlDataAdapter(cmd))
                {
                    adapter.Fill(tabel);   // Fill membuka & menutup koneksi otomatis
                }
            }

            return tabel;
        }

        // Daftar program studi untuk ComboBox filter (pakai SqlDataReader)
        public static List<KeyValuePair<int, string>> AmbilProgramStudi()
        {
            var hasil = new List<KeyValuePair<int, string>> { new KeyValuePair<int, string>(0, "Semua Program Studi") };

            using var conn = new SqlConnection(ConnectionString);
            using var cmd = new SqlCommand("SELECT ProgramId, ProgramName FROM Programs ORDER BY ProgramName", conn);
            conn.Open();

            using var reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                hasil.Add(new KeyValuePair<int, string>(reader.GetInt32(0), reader.GetString(1)));
            }
            return hasil;
        }

        // Ringkasan angka untuk kartu di atas (ExecuteScalar per query)
        public static (int mahasiswa, int matkul, int registrasi, int prodi) AmbilRingkasan()
        {
            using var conn = new SqlConnection(ConnectionString);
            conn.Open();

            int Hitung(string sql) => (int)new SqlCommand(sql, conn).ExecuteScalar();

            return (Hitung("SELECT COUNT(*) FROM Students"),
                    Hitung("SELECT COUNT(*) FROM Courses"),
                    Hitung("SELECT COUNT(*) FROM Registrations WHERE Status = 'Aktif'"),
                    Hitung("SELECT COUNT(*) FROM Programs"));
        }
    }
}
