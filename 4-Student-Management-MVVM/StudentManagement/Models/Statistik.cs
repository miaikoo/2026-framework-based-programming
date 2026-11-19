namespace StudentManagement.Models
{
    // Hasil hitungan statistik dari database
    public class Statistik
    {
        public int Total { get; set; }
        public int LakiLaki { get; set; }
        public int Perempuan { get; set; }
        public List<JumlahJurusan> PerJurusan { get; set; } = new List<JumlahJurusan>();
    }

    public class JumlahJurusan
    {
        public string Jurusan { get; set; }
        public int Jumlah { get; set; }
    }
}
