using System;
using System.Collections.Generic;
using System.Globalization;

class Program
{
    // Riwayat perhitungan disimpan di memory (hilang saat program ditutup)
    static List<string> riwayat = new List<string>();

    static void Main()
    {
        // Agar ikon/emoji tampil dengan benar di konsol
        Console.OutputEncoding = System.Text.Encoding.UTF8;

        // Desimal selalu memakai titik (2.5), tidak tergantung bahasa Windows
        CultureInfo.CurrentCulture = CultureInfo.InvariantCulture;

        int pilihan;
        do
        {
            TampilkanMenu();
            Console.Write("\nPilihan: ");

            if (!int.TryParse(Console.ReadLine(), out pilihan))
            {
                pilihan = 0;
            }

            Console.WriteLine();
            switch (pilihan)
            {
                case 1: HitungDuaAngka("+", "PENJUMLAHAN", ConsoleColor.Green); break;
                case 2: HitungDuaAngka("-", "PENGURANGAN", ConsoleColor.Blue); break;
                case 3: HitungDuaAngka("×", "PERKALIAN", ConsoleColor.Yellow); break;
                case 4: HitungDuaAngka("÷", "PEMBAGIAN", ConsoleColor.Magenta); break;
                case 5: HitungDuaAngka("^", "PANGKAT", ConsoleColor.Cyan); break;
                case 6: HitungAkar(); break;
                case 7: TampilkanRiwayat(); break;
                case 8:
                    Console.ForegroundColor = ConsoleColor.Green;
                    Console.WriteLine("Terima kasih telah menggunakan kalkulator. 🧮");
                    Console.ResetColor();
                    break;
                default:
                    TulisWarna("Pilihan tidak tersedia!", ConsoleColor.Red);
                    break;
            }

            if (pilihan != 8)
            {
                Console.WriteLine("\nTekan ENTER untuk melanjutkan...");
                Console.ReadLine();
            }
        } while (pilihan != 8);
    }

    static void TampilkanMenu()
    {
        try { Console.Clear(); } catch { /* output dialihkan, abaikan */ }

        Console.ForegroundColor = ConsoleColor.Cyan;
        Console.WriteLine("==================================");
        Console.WriteLine("      🧮 APLIKASI KALKULATOR 🧮");
        Console.WriteLine("==================================");
        Console.ResetColor();

        TulisMenuItem(1, ConsoleColor.Green, "+", "Penjumlahan");
        TulisMenuItem(2, ConsoleColor.Blue, "-", "Pengurangan");
        TulisMenuItem(3, ConsoleColor.Yellow, "×", "Perkalian");
        TulisMenuItem(4, ConsoleColor.Magenta, "÷", "Pembagian");
        TulisMenuItem(5, ConsoleColor.Cyan, "^", "Pangkat");
        TulisMenuItem(6, ConsoleColor.DarkYellow, "√", "Akar Kuadrat");
        TulisMenuItem(7, ConsoleColor.White, "≡", "Riwayat Perhitungan");
        TulisMenuItem(8, ConsoleColor.DarkGray, ">", "Keluar");
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

    // Membaca angka sampai input valid; menerima "2.5" maupun "2,5"
    static double BacaAngka(string label)
    {
        while (true)
        {
            Console.Write(label);
            string input = (Console.ReadLine() ?? "").Replace(',', '.');

            if (double.TryParse(input, out double angka))
            {
                return angka;
            }

            TulisWarna("Input harus berupa angka!", ConsoleColor.Red);
        }
    }

    // Operasi dengan dua angka: +, -, ×, ÷, ^
    static void HitungDuaAngka(string op, string judul, ConsoleColor warna)
    {
        TulisWarna("--- " + judul + " ---", warna);

        double a = BacaAngka("Angka pertama : ");
        double b = BacaAngka("Angka kedua   : ");

        double hasil;
        switch (op)
        {
            case "+": hasil = a + b; break;
            case "-": hasil = a - b; break;
            case "×": hasil = a * b; break;
            case "÷":
                if (b == 0)
                {
                    TulisWarna("Error: tidak bisa membagi dengan nol!", ConsoleColor.Red);
                    return;
                }
                hasil = a / b;
                break;
            case "^": hasil = Math.Pow(a, b); break;
            default: return;
        }

        TampilkanHasil(Format(a) + " " + op + " " + Format(b), hasil);
    }

    static void HitungAkar()
    {
        TulisWarna("--- AKAR KUADRAT ---", ConsoleColor.DarkYellow);

        double a = BacaAngka("Angka : ");

        if (a < 0)
        {
            TulisWarna("Error: akar dari bilangan negatif tidak terdefinisi!", ConsoleColor.Red);
            return;
        }

        TampilkanHasil("√" + Format(a), Math.Sqrt(a));
    }

    static void TampilkanHasil(string ekspresi, double hasil)
    {
        string baris = ekspresi + " = " + Format(hasil);
        riwayat.Add(baris);

        Console.Write("\nHasil : ");
        TulisWarna(baris, ConsoleColor.Green);
    }

    static void TampilkanRiwayat()
    {
        TulisWarna("--- RIWAYAT PERHITUNGAN ---", ConsoleColor.White);

        if (riwayat.Count == 0)
        {
            Console.WriteLine("Belum ada perhitungan.");
            return;
        }

        for (int i = 0; i < riwayat.Count; i++)
        {
            Console.WriteLine("{0,3}. {1}", i + 1, riwayat[i]);
        }
    }

    // Maksimal 4 angka desimal, tanpa nol berlebih: 2.5 bukan 2.5000
    static string Format(double angka)
    {
        return angka.ToString("0.####");
    }

    static void TulisWarna(string teks, ConsoleColor warna)
    {
        Console.ForegroundColor = warna;
        Console.WriteLine(teks);
        Console.ResetColor();
    }
}
