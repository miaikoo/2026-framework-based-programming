using Microsoft.Data.SqlClient;
using StudentManagement.Models;

namespace StudentManagement.Data
{
    // Semua akses database (ADO.NET) dikumpulkan di sini.
    // Selalu memakai parameter (@Nim, @Nama, ...) untuk mencegah SQL Injection.
    public class StudentRepository
    {
        public List<Student> GetAll()
        {
            return Query("SELECT Id, Nim, Nama, Jurusan, Gender, Email FROM Students ORDER BY Nim", null);
        }

        public List<Student> Search(string kataKunci)
        {
            return Query(@"SELECT Id, Nim, Nama, Jurusan, Gender, Email FROM Students
                           WHERE Nim LIKE @k OR Nama LIKE @k OR Jurusan LIKE @k OR Email LIKE @k
                           ORDER BY Nim",
                         cmd => cmd.Parameters.AddWithValue("@k", "%" + kataKunci + "%"));
        }

        public void Insert(Student s)
        {
            Execute(@"INSERT INTO Students (Nim, Nama, Jurusan, Gender, Email)
                      VALUES (@Nim, @Nama, @Jurusan, @Gender, @Email)", s);
        }

        public void Update(Student s)
        {
            Execute(@"UPDATE Students
                      SET Nim = @Nim, Nama = @Nama, Jurusan = @Jurusan, Gender = @Gender, Email = @Email
                      WHERE Id = @Id", s);
        }

        public void Delete(int id)
        {
            using var conn = new SqlConnection(Database.ConnectionString);
            using var cmd = new SqlCommand("DELETE FROM Students WHERE Id = @Id", conn);
            cmd.Parameters.AddWithValue("@Id", id);
            conn.Open();
            cmd.ExecuteNonQuery();
        }

        // Cek NIM ganda; abaikanId dipakai saat update agar data itu sendiri tidak dihitung
        public bool NimSudahAda(string nim, int abaikanId = 0)
        {
            using var conn = new SqlConnection(Database.ConnectionString);
            using var cmd = new SqlCommand("SELECT COUNT(*) FROM Students WHERE Nim = @Nim AND Id <> @Id", conn);
            cmd.Parameters.AddWithValue("@Nim", nim);
            cmd.Parameters.AddWithValue("@Id", abaikanId);
            conn.Open();
            return (int)cmd.ExecuteScalar() > 0;
        }

        public Statistik GetStatistik()
        {
            var stat = new Statistik();
            using var conn = new SqlConnection(Database.ConnectionString);
            conn.Open();

            // Total dan jumlah per gender dalam satu query
            using (var cmd = new SqlCommand(@"
                SELECT COUNT(*),
                       SUM(CASE WHEN Gender = 'Laki-laki' THEN 1 ELSE 0 END),
                       SUM(CASE WHEN Gender = 'Perempuan' THEN 1 ELSE 0 END)
                FROM Students", conn))
            using (var r = cmd.ExecuteReader())
            {
                if (r.Read())
                {
                    stat.Total = r.GetInt32(0);
                    stat.LakiLaki = r.IsDBNull(1) ? 0 : r.GetInt32(1);
                    stat.Perempuan = r.IsDBNull(2) ? 0 : r.GetInt32(2);
                }
            }

            // Jumlah per jurusan
            using (var cmd = new SqlCommand(
                "SELECT Jurusan, COUNT(*) FROM Students GROUP BY Jurusan ORDER BY COUNT(*) DESC, Jurusan", conn))
            using (var r = cmd.ExecuteReader())
            {
                while (r.Read())
                {
                    stat.PerJurusan.Add(new JumlahJurusan { Jurusan = r.GetString(0), Jumlah = r.GetInt32(1) });
                }
            }

            return stat;
        }

        // ===== Helper =====

        private List<Student> Query(string sql, Action<SqlCommand> isiParameter)
        {
            var hasil = new List<Student>();
            using var conn = new SqlConnection(Database.ConnectionString);
            using var cmd = new SqlCommand(sql, conn);
            isiParameter?.Invoke(cmd);
            conn.Open();

            using var reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                hasil.Add(new Student
                {
                    Id = reader.GetInt32(0),
                    Nim = reader.GetString(1),
                    Nama = reader.GetString(2),
                    Jurusan = reader.GetString(3),
                    Gender = reader.GetString(4),
                    Email = reader.GetString(5)
                });
            }
            return hasil;
        }

        private void Execute(string sql, Student s)
        {
            using var conn = new SqlConnection(Database.ConnectionString);
            using var cmd = new SqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@Id", s.Id);
            cmd.Parameters.AddWithValue("@Nim", s.Nim);
            cmd.Parameters.AddWithValue("@Nama", s.Nama);
            cmd.Parameters.AddWithValue("@Jurusan", s.Jurusan);
            cmd.Parameters.AddWithValue("@Gender", s.Gender);
            cmd.Parameters.AddWithValue("@Email", s.Email);
            conn.Open();
            cmd.ExecuteNonQuery();
        }
    }
}
