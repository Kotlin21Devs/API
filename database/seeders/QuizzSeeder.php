<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;

class QuizzSeeder extends Seeder
{
    public function run()
    {
        Quiz::create([
            'user_id' => 1, // Asumsi user dengan id 1 sudah ada
            'course_id' => 1, // Kursus: Pemrograman Dasar dengan Python
            'question' => 'Apa tipe data untuk menyimpan bilangan bulat di Python?',
            'option_a' => 'Integer (int) untuk bilangan bulat seperti 42',
            'option_b' => 'Float untuk bilangan desimal seperti 3.14',
            'option_c' => 'String (str) untuk teks seperti "hello"',
            'option_d' => 'Boolean (bool) untuk nilai benar atau salah',
            'correct_answer' => 'Integer (int) untuk bilangan bulat seperti 42',
        ]);

        Quiz::create([
            'user_id' => 1,
            'course_id' => 2, // Kursus: JavaScript Lanjutan
            'question' => 'Manakah metode untuk menangani promise di JavaScript?',
            'option_a' => 'Callback untuk fungsi asinkronus tradisional',
            'option_b' => 'then untuk menangani hasil promise',
            'option_c' => 'setTimeout untuk penundaan eksekusi',
            'option_d' => 'asyncFunction untuk mendefinisikan fungsi async',
            'correct_answer' => 'then untuk menangani hasil promise',
        ]);

        Quiz::create([
            'user_id' => 1,
            'course_id' => 3, // Kursus: Data Science dengan Python
            'question' => 'Library apa yang digunakan untuk visualisasi data di Python?',
            'option_a' => 'Pandas untuk manipulasi data tabular',
            'option_b' => 'NumPy untuk operasi numerik',
            'option_c' => 'Matplotlib untuk membuat grafik dan visualisasi',
            'option_d' => 'Scikit-learn untuk machine learning',
            'correct_answer' => 'Matplotlib untuk membuat grafik dan visualisasi',
        ]);
    }
}