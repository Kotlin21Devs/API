<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // Course 1: Pemrograman Dasar dengan Python
        // Modul 1: Dasar-Dasar Python (module_id = 1)
        Lesson::create([
            'course_id' => 1,
            'module_id' => 1,
            'title' => 'Pengantar Variabel dan Tipe Data',
            'content' => 'Pelajaran ini membahas konsep dasar variabel dalam Python...',
            'order' => 1,
        ]);

        // Modul 2: Struktur Kontrol Python (module_id = 2)
        Lesson::create([
            'course_id' => 1,
            'module_id' => 2,
            'title' => 'Struktur Kontrol: If dan Loop',
            'content' => 'Pelajari cara menggunakan pernyataan if, elif, dan else...',
            'order' => 1,
        ]);

        // Course 2: JavaScript Lanjutan
        // Modul 3: JavaScript Asinkronus (module_id = 3)
        Lesson::create([
            'course_id' => 2,
            'module_id' => 3,
            'title' => 'Pemrograman Asinkronus dengan Promises',
            'content' => 'Pelajaran ini menjelaskan konsep pemrograman asinkronus...',
            'order' => 1,
        ]);

        // Modul 4: Pemrograman Fungsional (module_id = 4)
        Lesson::create([
            'course_id' => 2,
            'module_id' => 4,
            'title' => 'Closures dan Lexical Scope',
            'content' => 'Memahami closures dan lexical scope adalah kunci...',
            'order' => 1,
        ]);

        // Course 3: Data Science dengan Python
        // Modul 5: Manipulasi Data (module_id = 5)
        Lesson::create([
            'course_id' => 3,
            'module_id' => 5,
            'title' => 'Pengenalan Pandas untuk Manipulasi Data',
            'content' => 'Pelajaran ini memperkenalkan Pandas...',
            'order' => 1,
        ]);

        // Modul 6: Visualisasi Data (module_id = 6)
        Lesson::create([
            'course_id' => 3,
            'module_id' => 6,
            'title' => 'Visualisasi Data dengan Matplotlib',
            'content' => 'Pelajari cara membuat visualisasi data...',
            'order' => 1,
        ]);
    }
}