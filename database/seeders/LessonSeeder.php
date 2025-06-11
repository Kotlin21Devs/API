<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // Modul 1: Dasar-Dasar Python (module_id = 1, course_id = 1)
        Lesson::create([
            'module_id' => 1,
            'title' => 'Pengantar Variabel dan Tipe Data',
            'content' => 'Pelajaran ini membahas konsep dasar variabel dalam Python, termasuk tipe data seperti integer, float, string, dan boolean. Anda akan belajar cara mendeklarasikan variabel, aturan penamaan, dan operasi dasar seperti konkatenasi string dan aritmatika. Kami juga akan menjelaskan pentingnya tipe data dinamis di Python dan bagaimana memeriksanya menggunakan fungsi type().',
            'order' => 1,
        ]);

        // Modul 2: Struktur Kontrol Python (module_id = 2, course_id = 1)
        Lesson::create([
            'module_id' => 2,
            'title' => 'Struktur Kontrol: If dan Loop',
            'content' => 'Pelajari cara menggunakan pernyataan if, elif, dan else untuk membuat keputusan dalam kode Anda. Pelajaran ini juga mencakup loop for dan while untuk mengulang tugas. Kami akan menunjukkan contoh praktis seperti memeriksa input pengguna dan mengiterasi daftar, serta menjelaskan konsep seperti break, continue, dan pass untuk mengontrol alur loop.',
            'order' => 1,
        ]);

        // Modul 3: JavaScript Asinkronus (module_id = 3, course_id = 2)
        Lesson::create([
            'module_id' => 3,
            'title' => 'Pemrograman Asinkronus dengan Promises',
            'content' => 'Pelajaran ini menjelaskan konsep pemrograman asinkronus di JavaScript menggunakan Promises. Anda akan belajar cara membuat dan menangani Promises, menangkap error dengan .catch(), dan menggabungkan beberapa Promises dengan Promise.all(). Kami juga akan membahas kasus penggunaan nyata seperti mengambil data dari API menggunakan fetch().',
            'order' => 1,
        ]);

        // Modul 4: Pemrograman Fungsional (module_id = 4, course_id = 2)
        Lesson::create([
            'module_id' => 4,
            'title' => 'Closures dan Lexical Scope',
            'content' => 'Memahami closures dan lexical scope adalah kunci untuk menjadi pengembang JavaScript yang mahir. Pelajaran ini menjelaskan bagaimana fungsi dalam JavaScript mempertahankan akses ke variabel dari scope luarnya, bahkan setelah fungsi tersebut selesai dieksekusi. Kami akan memberikan contoh seperti membuat counter pribadi dan fungsi yang mengembalikan fungsi lain.',
            'order' => 1,
        ]);

        // Modul 5: Manipulasi Data (module_id = 5, course_id = 3)
        Lesson::create([
            'module_id' => 5,
            'title' => 'Pengenalan Pandas untuk Manipulasi Data',
            'content' => 'Pelajaran ini memperkenalkan Pandas, pustaka Python yang kuat untuk analisis data. Anda akan belajar cara membuat DataFrame, memfilter data, menangani nilai yang hilang, dan melakukan operasi seperti pengelompokan dan penggabungan. Kami akan menggunakan dataset nyata untuk menunjukkan cara membersihkan dan menyiapkan data untuk analisis.',
            'order' => 1,
        ]);

        // Modul 6: Visualisasi Data (module_id = 6, course_id = 3)
        Lesson::create([
            'module_id' => 6,
            'title' => 'Visualisasi Data dengan Matplotlib',
            'content' => 'Pelajari cara membuat visualisasi data yang informatif menggunakan Matplotlib. Pelajaran ini mencakup pembuatan grafik seperti plot garis, scatter, histogram, dan bar. Anda akan belajar cara menyesuaikan grafik dengan label, judul, dan legenda, serta bagaimana menggabungkan Matplotlib dengan Pandas untuk memvisualisasikan hasil analisis data.',
            'order' => 1,
        ]);
    }
}