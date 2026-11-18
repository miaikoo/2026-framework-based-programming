using System;
using System.Collections.Generic;

class Mahasiswa
{
    public string NIM { get; set; }
    public string Nama { get; set; }
    public string Prodi { get; set; }
    public double IPK { get; set; }

    public Mahasiswa(string nim, string nama, string prodi, double ipk)
    {
        NIM = nim; Nama = nama; Prodi = prodi; IPK = ipk;
    }
}

class Program
{
    // List untuk menyimpan data mahasiswa (di memory)
    static List<Mahasiswa> daftarMahasiswa = new List<Mahasiswa>();

    static void Main()
    {
        // Agar ikon/emoji tampil dengan benar di konsol
        Console.OutputEncoding = System.Text.Encoding.UTF8;

        // Pakai format angka "netral" agar desimal selalu memakai titik (3.50),
        // tidak tergantung pengaturan bahasa Windows (Indonesia memakai koma)
        System.Globalization.CultureInfo.CurrentCulture = System.Globalization.CultureInfo.InvariantCulture;

        int pilihan;
        do
        {
            TampilkanMenu();
            Console.Write("\nPilihan: ");
            string input = Console.ReadLine();

            if (!int.TryParse(input, out pilihan))
            {
                pilihan = 0;
            }

            Console.WriteLine();
            switch (pilihan)
            {
                case 1: TambahMahasiswa(); break;
                case 2: TampilkanMahasiswa(); break;
                case 3: CariMahasiswa(); break;
                case 4: HapusMahasiswa(); break;
                case 5:
                    Console.ForegroundColor = ConsoleColor.Green;
                    Console.WriteLine("Terima kasih telah menggunakan program. 🎓");
                    Console.ResetColor();
                    break;
                default:
                    Console.ForegroundColor = ConsoleColor.Red;
                    Console.WriteLine("Pilihan tidak tersedia!");
                    Console.ResetColor();
                    break;
            }

            if (pilihan != 5)
            {
                Console.WriteLine("\nTekan ENTER untuk melanjutkan...");
                Console.ReadLine();
            }
        } while (pilihan != 5);
    }

    static void TampilkanMenu()
    {
        try { Console.Clear(); } catch { /* output dialihkan, abaikan */ }

        Console.ForegroundColor = ConsoleColor.Cyan;
        Console.WriteLine("==================================");
        Console.WriteLine("    🎓 SISTEM DATA MAHASISWA 🎓");
        Console.WriteLine("==================================");
        Console.ResetColor();

        TulisMenuItem(1, ConsoleColor.Green, "+", "Tambah Mahasiswa");
        TulisMenuItem(2, ConsoleColor.Blue, "=", "Tampilkan Mahasiswa");
        TulisMenuItem(3, ConsoleColor.Yellow, "?", "Cari Mahasiswa");
        TulisMenuItem(4, ConsoleColor.Red, "x", "Hapus Mahasiswa");
        TulisMenuItem(5, ConsoleColor.DarkGray, ">", "Keluar");
    }

    // Menulis satu baris menu dengan "lencana" nomor berwarna
    static void TulisMenuItem(int nomor, ConsoleColor warnaLencana, string ikon, string teks)
    {
        Console.Write("   ");

        Console.BackgroundColor = warnaLencana;
        Console.ForegroundColor = ConsoleColor.Black;
        Console.Write(" " + nomor + " ");
        Console.ResetColor();

        Console.ForegroundColor = warnaLencana;
        Console.Write("  " + ikon);
        Console.ResetColor();

        Console.WriteLine("  " + teks);
    }

    static void TambahMahasiswa()
    {
        Console.ForegroundColor = ConsoleColor.Green;
        Console.WriteLine("--- TAMBAH MAHASISWA ---");
        Console.ResetColor();

        Console.Write("NIM            : ");
        string nim = Console.ReadLine();

        if (CariBerdasarkanNIM(nim) != null)
        {
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("NIM sudah terdaftar!");
            Console.ResetColor();
            return;
        }

        Console.Write("Nama           : ");
        string nama = Console.ReadLine();

        Console.Write("Program Studi  : ");
        string prodi = Console.ReadLine();

        double ipk;
        while (true)
        {
            Console.Write("IPK            : ");
            string inputIpk = (Console.ReadLine() ?? "").Replace(',', '.'); // terima "3,5" maupun "3.5"
            if (double.TryParse(inputIpk, out ipk))
            {
                if (ipk >= 0 && ipk <= 4)
                {
                    break;
                }
            }

            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("IPK harus berupa angka 0 - 4.");
            Console.ResetColor();
        }

        Mahasiswa mahasiswa = new Mahasiswa(nim, nama, prodi, ipk);
        daftarMahasiswa.Add(mahasiswa);

        Console.ForegroundColor = ConsoleColor.Green;
        Console.WriteLine("Data mahasiswa berhasil ditambahkan.");
        Console.ResetColor();
    }

    static void TampilkanMahasiswa()
    {
        Console.ForegroundColor = ConsoleColor.Blue;
        Console.WriteLine("--- DAFTAR MAHASISWA ---");
        Console.ResetColor();

        if (daftarMahasiswa.Count == 0)
        {
            Console.WriteLine("Belum ada data mahasiswa.");
            return;
        }

        Console.WriteLine("{0,-12} {1,-20} {2,-20} {3,5}", "NIM", "Nama", "Prodi", "IPK");
        Console.WriteLine(new string('-', 60));

        foreach (Mahasiswa m in daftarMahasiswa)
        {
            Console.WriteLine("{0,-12} {1,-20} {2,-20} {3,5:F2}", m.NIM, m.Nama, m.Prodi, m.IPK);
        }
    }

    static void CariMahasiswa()
    {
        Console.ForegroundColor = ConsoleColor.Yellow;
        Console.WriteLine("--- CARI MAHASISWA ---");
        Console.ResetColor();

        Console.Write("Masukkan NIM: ");
        string nimCari = Console.ReadLine();

        Mahasiswa mahasiswaDitemukan = CariBerdasarkanNIM(nimCari);

        if (mahasiswaDitemukan != null)
        {
            Console.WriteLine("\nData ditemukan:");
            Console.WriteLine("NIM   : " + mahasiswaDitemukan.NIM);
            Console.WriteLine("Nama  : " + mahasiswaDitemukan.Nama);
            Console.WriteLine("Prodi : " + mahasiswaDitemukan.Prodi);
            Console.WriteLine("IPK   : " + mahasiswaDitemukan.IPK.ToString("F2"));
        }
        else
        {
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("Data mahasiswa tidak ditemukan.");
            Console.ResetColor();
        }
    }

    static void HapusMahasiswa()
    {
        Console.ForegroundColor = ConsoleColor.Red;
        Console.WriteLine("--- HAPUS MAHASISWA ---");
        Console.ResetColor();

        Console.Write("Masukkan NIM: ");
        string nimHapus = Console.ReadLine();

        Mahasiswa mahasiswaDitemukan = CariBerdasarkanNIM(nimHapus);

        if (mahasiswaDitemukan != null)
        {
            daftarMahasiswa.Remove(mahasiswaDitemukan);
            Console.ForegroundColor = ConsoleColor.Green;
            Console.WriteLine("Data mahasiswa berhasil dihapus.");
            Console.ResetColor();
        }
        else
        {
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("Data mahasiswa tidak ditemukan.");
            Console.ResetColor();
        }
    }

    // Pencarian linear berdasarkan NIM (tidak membedakan huruf besar/kecil)
    static Mahasiswa CariBerdasarkanNIM(string nim)
    {
        foreach (Mahasiswa m in daftarMahasiswa)
        {
            if (m.NIM.Equals(nim, StringComparison.OrdinalIgnoreCase))
            {
                return m;
            }
        }
        return null;
    }
}
