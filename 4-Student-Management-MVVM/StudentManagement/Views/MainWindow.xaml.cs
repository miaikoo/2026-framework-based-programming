using System.Windows;
using StudentManagement.ViewModels;

namespace StudentManagement.Views
{
    // Code-behind sengaja dibuat minimal: semua logika ada di MainViewModel
    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();

            // Satu-satunya hal yang dilakukan View: menyediakan dialog konfirmasi untuk ViewModel
            if (DataContext is MainViewModel vm)
            {
                vm.Konfirmasi = pesan => MessageBox.Show(pesan, "Konfirmasi",
                    MessageBoxButton.YesNo, MessageBoxImage.Question) == MessageBoxResult.Yes;
            }
        }
    }
}
