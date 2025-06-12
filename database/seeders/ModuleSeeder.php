<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        // Course 1: Pemrograman Dasar dengan Python
        Module::create([
            'course_id' => 1,
            'title' => 'Dasar-Dasar Python',
            'content' => 'Modul ini mencakup konsep dasar pemrograman dengan Python.',
            'order' => 1,
        ]);

        Module::create([
            'course_id' => 1,
            'title' => 'Struktur Kontrol Python',
            'content' => 'Modul ini membahas struktur kontrol seperti if dan loop.',
            'order' => 2,
        ]);

        // Course 2: JavaScript Lanjutan
        Module::create([
            'course_id' => 2,
            'title' => 'JavaScript Asinkronus',
            'content' => 'Modul ini mencakup pemrograman asinkronus di JavaScript.',
            'order' => 1,
        ]);

        Module::create([
            'course_id' => 2,
            'title' => 'Pemrograman Fungsional',
            'content' => 'Modul ini membahas closures dan lexical scope.',
            'order' => 2,
        ]);

        // Course 3: Data Science dengan Python
        Module::create([
            'course_id' => 3,
            'title' => 'Manipulasi Data',
            'content' => 'Modul ini memperkenalkan manipulasi data dengan Pandas.',
            'order' => 1,
        ]);

        Module::create([
            'course_id' => 3,
            'title' => 'Visualisasi Data',
            'content' => 'Modul ini membahas visualisasi data dengan Matplotlib.',
            'order' => 2,
        ]);
    }
}