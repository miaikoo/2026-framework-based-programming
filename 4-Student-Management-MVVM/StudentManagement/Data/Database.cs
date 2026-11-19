using Microsoft.Data.SqlClient;

namespace StudentManagement.Data
{
    // Pengaturan koneksi + pembuatan database/tabel otomatis saat aplikasi pertama kali jalan
    public static class Database
    {
        // Ganti "localhost" jika nama server berbeda, misal "localhost\SQLEXPRESS" atau "(localdb)\MSSQLLocalDB"
        private const string Server = "localhost";
        private const string NamaDatabase = "StudentDB";

        public static string ConnectionString =>
            $"Server={Server};Database={NamaDatabase};Trusted_Connection=True;TrustServerCertificate=True;";

        private static string MasterConnectionString =>
            $"Server={Server};Database=master;Trusted_Connection=True;TrustServerCertificate=True;";

        public static void Inisialisasi()
        {
            // 1. Buat database jika belum ada
            using (var conn = new SqlConnection(MasterConnectionString))
            {
                conn.Open();
                var cmd = new SqlCommand(
                    $"IF DB_ID('{NamaDatabase}') IS NULL CREATE DATABASE [{NamaDatabase}];", conn);
                cmd.ExecuteNonQuery();
            }

            // 2. Buat tabel jika belum ada
            using (var conn = new SqlConnection(ConnectionString))
            {
                conn.Open();
                var cmd = new SqlCommand(@"
                    IF OBJECT_ID('dbo.Students', 'U') IS NULL
                    CREATE TABLE dbo.Students (
                        Id      INT IDENTITY(1,1) PRIMARY KEY,
                        Nim     VARCHAR(20)   NOT NULL UNIQUE,
                        Nama    NVARCHAR(100) NOT NULL,
                        Jurusan NVARCHAR(100) NOT NULL,
                        Gender  NVARCHAR(20)  NOT NULL,
                        Email   NVARCHAR(100) NOT NULL
                    );", conn);
                cmd.ExecuteNonQuery();
            }
        }
    }
}
