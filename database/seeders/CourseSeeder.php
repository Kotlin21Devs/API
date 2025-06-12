<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Course::create([
            'title' => 'Pemrograman Dasar dengan Python',
            'category' => 'Basic',
            'description' => 'Kursus ini memperkenalkan dasar-dasar pemrograman menggunakan Python, mencakup variabel, struktur kontrol, fungsi, dan pemrograman berorientasi objek. Cocok untuk pemula yang ingin memahami logika pemrograman.',
        ]);
        
        Course::create([
            'title' => 'JavaScript Lanjutan',
            'category' => 'Advanced',
            'description' => 'Pelajari konsep lanjutan JavaScript seperti asynchronous programming, closures, dan modularitas. Kursus ini dirancang untuk pengembang yang ingin meningkatkan keahlian front-end dan back-end.',
        ]);
        
        Course::create([
            'title' => 'Data Science dengan Python',
            'category' => 'Intermediate',
            'description' => 'Kursus ini mengajarkan penggunaan Python untuk analisis data, termasuk Pandas, NumPy, dan visualisasi data dengan Matplotlib. Ideal untuk mereka yang ingin masuk ke bidang data science.',
        ]);
        
    }
}
