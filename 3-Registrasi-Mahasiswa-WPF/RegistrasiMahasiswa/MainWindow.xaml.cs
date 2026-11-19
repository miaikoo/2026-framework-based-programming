using System.Collections.ObjectModel;
using System.Text.RegularExpressions;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Media;

namespace RegistrasiMahasiswa
{
    public partial class MainWindow : Window
    {
        // ObservableCollection: DataGrid otomatis ter-update saat data ditambah/dihapus
        private readonly ObservableCollection<Mahasiswa> daftarMahasiswa = new ObservableCollection<Mahasiswa>();

        public MainWindow()
        {
            InitializeComponent();
            dgMahasiswa.ItemsSource = daftarMahasiswa;
        }

        private void BtnDaftar_Click(object sender, RoutedEventArgs e)
        {
            string nim = txtNim.Text.Trim();
            string nama = txtNama.Text.Trim();
            string email = txtEmail.Text.Trim();

            // ===== Validasi berurutan; berhenti di kesalahan pertama =====
            if (nim == "" || nama == "" || email == "" || txtAlamat.Text.Trim() == "")
            {
                TampilkanPesan("Semua kolom wajib diisi!", true);
                return;
            }
            if (!Regex.IsMatch(nim, @"^\d{5,12}$"))
            {
                TampilkanPesan("NIM harus berupa angka 5–12 digit.", true);
                return;
            }
            if (daftarMahasiswa.Any(m => m.NIM == nim))
            {
                TampilkanPesan("NIM " + nim + " sudah terdaftar.", true);
                return;
            }
            if (!Regex.IsMatch(email, @"^[^@\s]+@[^@\s]+\.[^@\s]+$"))
            {
                TampilkanPesan("Format email tidak valid.", true);
                return;
            }
            if (cmbProdi.SelectedItem == null)
            {
                TampilkanPesan("Pilih program studi.", true);
                return;
            }
            if (rbLaki.IsChecked != true && rbPerempuan.IsChecked != true)
            {
                TampilkanPesan("Pilih jenis kelamin.", true);
                return;
            }
            if (dpTanggalLahir.SelectedDate == null || dpTanggalLahir.SelectedDate > DateTime.Today)
            {
                TampilkanPesan("Isi tanggal lahir dengan benar.", true);
                return;
            }
            if (chkSetuju.IsChecked != true)
            {
                TampilkanPesan("Centang pernyataan persetujuan terlebih dahulu.", true);
                return;
            }

            // ===== Semua valid -> simpan =====
            daftarMahasiswa.Add(new Mahasiswa
            {
                NIM = nim,
                Nama = nama,
                Email = email,
                Prodi = ((ComboBoxItem)cmbProdi.SelectedItem).Content.ToString(),
                JenisKelamin = rbLaki.IsChecked == true ? "Laki-laki" : "Perempuan",
                TanggalLahir = dpTanggalLahir.SelectedDate.Value,
                Alamat = txtAlamat.Text.Trim()
            });

            PerbaruiJumlah();
            KosongkanForm();
            TampilkanPesan("✔ " + nama + " berhasil didaftarkan!", false);
        }

        private void BtnReset_Click(object sender, RoutedEventArgs e)
        {
            KosongkanForm();
            lblPesan.Text = "";
        }

        private void BtnHapus_Click(object sender, RoutedEventArgs e)
        {
            if (dgMahasiswa.SelectedItem is not Mahasiswa terpilih)
            {
                MessageBox.Show("Pilih data di tabel terlebih dahulu.", "Hapus Data",
                                MessageBoxButton.OK, MessageBoxImage.Information);
                return;
            }

            var jawab = MessageBox.Show("Hapus data " + terpilih.Nama + "?", "Konfirmasi",
                                        MessageBoxButton.YesNo, MessageBoxImage.Question);
            if (jawab == MessageBoxResult.Yes)
            {
                daftarMahasiswa.Remove(terpilih);
                PerbaruiJumlah();
            }
        }

        private void KosongkanForm()
        {
            txtNim.Clear();
            txtNama.Clear();
            txtEmail.Clear();
            txtAlamat.Clear();
            cmbProdi.SelectedIndex = -1;
            rbLaki.IsChecked = false;
            rbPerempuan.IsChecked = false;
            dpTanggalLahir.SelectedDate = null;
            chkSetuju.IsChecked = false;
            txtNim.Focus();
        }

        private void PerbaruiJumlah()
        {
            lblJumlah.Text = "Mahasiswa Terdaftar (" + daftarMahasiswa.Count + ")";
        }

        private void TampilkanPesan(string pesan, bool error)
        {
            lblPesan.Text = pesan;
            lblPesan.Foreground = new SolidColorBrush(error ? Color.FromRgb(0xDC, 0x26, 0x26)
                                                            : Color.FromRgb(0x16, 0xA3, 0x4A));
        }
    }
}
