namespace StudentManagement.Models
{
    // MODEL: representasi satu baris tabel Students di database
    public class Student
    {
        public int Id { get; set; }
        public string Nim { get; set; }
        public string Nama { get; set; }
        public string Jurusan { get; set; }
        public string Gender { get; set; }
        public string Email { get; set; }
    }
}
