namespace RegistrasiMahasiswa
{
    // Cetakan data satu mahasiswa yang mendaftar
    public class Mahasiswa
    {
        public string NIM { get; set; }
        public string Nama { get; set; }
        public string Email { get; set; }
        public string Prodi { get; set; }
        public string JenisKelamin { get; set; }
        public DateTime TanggalLahir { get; set; }
        public string Alamat { get; set; }

        // Dipakai DataGrid untuk menampilkan tanggal dengan format rapi
        public string TanggalLahirTeks => TanggalLahir.ToString("dd/MM/yyyy");
    }
}
