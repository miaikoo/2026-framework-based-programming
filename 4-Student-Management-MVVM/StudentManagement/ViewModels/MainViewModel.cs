using System.Collections.ObjectModel;
using System.Text.RegularExpressions;
using System.Windows.Input;
using StudentManagement.Data;
using StudentManagement.Models;

namespace StudentManagement.ViewModels
{
    // VIEWMODEL: menyimpan state layar + logika, tanpa tahu apa pun tentang kontrol UI
    public class MainViewModel : BaseViewModel
    {
        private readonly StudentRepository repo = new StudentRepository();

        // ===== Data untuk View =====
        public ObservableCollection<Student> Students { get; } = new ObservableCollection<Student>();
        public ObservableCollection<JumlahJurusan> StatistikJurusan { get; } = new ObservableCollection<JumlahJurusan>();

        public List<string> DaftarJurusan { get; } = new List<string>
        {
            "Teknik Informatika", "Sistem Informasi", "Teknologi Informasi",
            "Rekayasa Perangkat Lunak", "Sains Data"
        };

        // ===== Field formulir (two-way binding) =====
        private string nim, nama, jurusan, email, cari, pesan;
        private bool isLaki = true, isPerempuan, pesanError;
        private int total, jumlahLaki, jumlahPerempuan;
        private Student selectedStudent;

        public string Nim { get => nim; set => Set(ref nim, value); }
        public string Nama { get => nama; set => Set(ref nama, value); }
        public string Jurusan { get => jurusan; set => Set(ref jurusan, value); }
        public string Email { get => email; set => Set(ref email, value); }
        public bool IsLaki { get => isLaki; set => Set(ref isLaki, value); }
        public bool IsPerempuan { get => isPerempuan; set => Set(ref isPerempuan, value); }

        public string Cari
        {
            get => cari;
            set { if (Set(ref cari, value)) MuatData(); }   // pencarian langsung saat mengetik
        }

        public string Pesan { get => pesan; set => Set(ref pesan, value); }
        public bool PesanError { get => pesanError; set => Set(ref pesanError, value); }

        public int Total { get => total; set => Set(ref total, value); }
        public int JumlahLaki { get => jumlahLaki; set => Set(ref jumlahLaki, value); }
        public int JumlahPerempuan { get => jumlahPerempuan; set => Set(ref jumlahPerempuan, value); }

        // Saat baris tabel dipilih, isi formulir dengan datanya
        public Student SelectedStudent
        {
            get => selectedStudent;
            set
            {
                if (!Set(ref selectedStudent, value) || value == null) return;
                Nim = value.Nim;
                Nama = value.Nama;
                Jurusan = value.Jurusan;
                Email = value.Email;
                IsLaki = value.Gender == "Laki-laki";
                IsPerempuan = !IsLaki;
                Pesan = "";
            }
        }

        // ===== Command untuk tombol =====
        public ICommand TambahCommand { get; }
        public ICommand UbahCommand { get; }
        public ICommand HapusCommand { get; }
        public ICommand BersihkanCommand { get; }

        // Diisi oleh View untuk menampilkan dialog konfirmasi (ViewModel tidak memanggil MessageBox langsung)
        public Func<string, bool> Konfirmasi { get; set; } = _ => true;

        public MainViewModel()
        {
            TambahCommand = new RelayCommand(Tambah);
            UbahCommand = new RelayCommand(Ubah, () => SelectedStudent != null);
            HapusCommand = new RelayCommand(Hapus, () => SelectedStudent != null);
            BersihkanCommand = new RelayCommand(Bersihkan);

            try
            {
                Database.Inisialisasi();
                MuatData();
            }
            catch (Exception ex)
            {
                TampilkanPesan("Gagal terhubung ke database: " + ex.Message, true);
            }
        }

        // ===== CRUD =====

        private void Tambah()
        {
            if (!Validasi(0)) return;
            Jalankan(() => repo.Insert(BuatStudent(0)), "Data " + Nama.Trim() + " berhasil ditambahkan.");
        }

        private void Ubah()
        {
            if (!Validasi(SelectedStudent.Id)) return;
            Jalankan(() => repo.Update(BuatStudent(SelectedStudent.Id)), "Data " + Nama.Trim() + " berhasil diperbarui.");
        }

        private void Hapus()
        {
            var s = SelectedStudent;
            if (!Konfirmasi("Hapus data " + s.Nama + " (" + s.Nim + ")?")) return;
            Jalankan(() => repo.Delete(s.Id), "Data " + s.Nama + " berhasil dihapus.");
        }

        private void Bersihkan()
        {
            KosongkanForm();
            Pesan = "";
        }

        // Menjalankan operasi database, lalu memuat ulang tabel & statistik
        private void Jalankan(Action aksi, string pesanSukses)
        {
            try
            {
                aksi();
                KosongkanForm();
                MuatData();
                TampilkanPesan(pesanSukses, false);
            }
            catch (Exception ex)
            {
                TampilkanPesan("Terjadi kesalahan database: " + ex.Message, true);
            }
        }

        private void MuatData()
        {
            try
            {
                var data = string.IsNullOrWhiteSpace(Cari) ? repo.GetAll() : repo.Search(Cari.Trim());
                Students.Clear();
                foreach (var s in data) Students.Add(s);

                var stat = repo.GetStatistik();
                Total = stat.Total;
                JumlahLaki = stat.LakiLaki;
                JumlahPerempuan = stat.Perempuan;
                StatistikJurusan.Clear();
                foreach (var j in stat.PerJurusan) StatistikJurusan.Add(j);
            }
            catch (Exception ex)
            {
                TampilkanPesan("Gagal memuat data: " + ex.Message, true);
            }
        }

        // ===== Helper =====

        private bool Validasi(int idSaatIni)
        {
            if (string.IsNullOrWhiteSpace(Nim) || string.IsNullOrWhiteSpace(Nama) ||
                string.IsNullOrWhiteSpace(Jurusan) || string.IsNullOrWhiteSpace(Email))
                return Gagal("Semua kolom wajib diisi!");

            if (!Regex.IsMatch(Nim.Trim(), @"^\d{5,20}$"))
                return Gagal("NIM harus berupa angka (minimal 5 digit).");

            if (!Regex.IsMatch(Email.Trim(), @"^[^@\s]+@[^@\s]+\.[^@\s]+$"))
                return Gagal("Format email tidak valid.");

            try
            {
                if (repo.NimSudahAda(Nim.Trim(), idSaatIni))
                    return Gagal("NIM " + Nim.Trim() + " sudah terdaftar.");
            }
            catch (Exception ex)
            {
                return Gagal("Gagal mengecek NIM: " + ex.Message);
            }

            return true;
        }

        private bool Gagal(string pesan)
        {
            TampilkanPesan(pesan, true);
            return false;
        }

        private Student BuatStudent(int id) => new Student
        {
            Id = id,
            Nim = Nim.Trim(),
            Nama = Nama.Trim(),
            Jurusan = Jurusan,
            Gender = IsLaki ? "Laki-laki" : "Perempuan",
            Email = Email.Trim()
        };

        private void KosongkanForm()
        {
            Nim = Nama = Email = "";
            Jurusan = null;
            IsLaki = true;
            IsPerempuan = false;
            SelectedStudent = null;
        }

        private void TampilkanPesan(string teks, bool error)
        {
            PesanError = error;
            Pesan = teks;
        }
    }
}
