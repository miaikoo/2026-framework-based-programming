using System.ComponentModel;
using System.Runtime.CompilerServices;

namespace StudentManagement.ViewModels
{
    // Dasar semua ViewModel: memberi tahu View setiap kali sebuah properti berubah
    public abstract class BaseViewModel : INotifyPropertyChanged
    {
        public event PropertyChangedEventHandler PropertyChanged;

        protected void OnPropertyChanged([CallerMemberName] string nama = null)
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nama));
        }

        // Mengubah nilai field lalu memicu notifikasi hanya jika nilainya benar-benar berubah
        protected bool Set<T>(ref T field, T nilai, [CallerMemberName] string nama = null)
        {
            if (EqualityComparer<T>.Default.Equals(field, nilai)) return false;
            field = nilai;
            OnPropertyChanged(nama);
            return true;
        }
    }
}
