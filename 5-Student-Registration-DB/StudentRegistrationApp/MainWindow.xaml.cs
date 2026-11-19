using System.Windows;
using System.Windows.Media;

namespace StudentRegistrationApp
{
    public partial class MainWindow : Window
    {
        private bool siap;   // mencegah query berjalan saat ComboBox masih diisi

        public MainWindow()
        {
            InitializeComponent();
        }

        private void Window_Loaded(object sender, RoutedEventArgs e)
        {
            try
            {
                cmbTampilan.ItemsSource = DataAccess.DaftarTampilan;
                cmbTampilan.SelectedIndex = 0;

                cmbProdi.ItemsSource = DataAccess.AmbilProgramStudi();
                cmbProdi.SelectedIndex = 0;

                siap = true;
                MuatData();
            }
            catch (Exception ex)
            {
                TampilkanError("Gagal terhubung ke database: " + ex.Message);
            }
        }

        // Dipanggil setiap kali tampilan, prodi, atau kata kunci berubah
        private void Filter_Changed(object sender, EventArgs e)
        {
            if (siap) MuatData();
        }

        private void BtnMuatUlang_Click(object sender, RoutedEventArgs e)
        {
            MuatData();
        }

        private void MuatData()
        {
            try
            {
                string tampilan = (string)cmbTampilan.SelectedItem;
                int programId = (int)(cmbProdi.SelectedValue ?? 0);

                var tabel = DataAccess.Ambil(tampilan, programId, txtCari.Text);
                dgData.ItemsSource = tabel.DefaultView;   // DataTable -> DataGrid

                var r = DataAccess.AmbilRingkasan();
                lblMahasiswa.Text = r.mahasiswa.ToString();
                lblProdi.Text = r.prodi.ToString();
                lblMatkul.Text = r.matkul.ToString();
                lblRegistrasi.Text = r.registrasi.ToString();

                lblStatus.Foreground = new SolidColorBrush(Color.FromRgb(0x64, 0x74, 0x8B));
                lblStatus.Text = $"{tabel.Rows.Count} baris ditampilkan · {tampilan} · dimuat {DateTime.Now:HH:mm:ss}";
            }
            catch (Exception ex)
            {
                TampilkanError("Gagal memuat data: " + ex.Message);
            }
        }

        private void TampilkanError(string pesan)
        {
            dgData.ItemsSource = null;
            lblStatus.Foreground = Brushes.Crimson;
            lblStatus.Text = pesan;
        }
    }
}
