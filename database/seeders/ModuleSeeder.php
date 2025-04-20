<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan 'content' dengan nilai default kosong
        Module::create([
            'course_id' => 1,
            'title' => 'Basic Programming',
            'content' => 'This module covers the basics of programming.',
        ]);

        Module::create([
            'course_id' => 2,
            'title' => 'Advanced JavaScript',
            'content' => 'This module covers advanced JavaScript topics.',
        ]);

        Module::create([
            'course_id' => 3,
            'title' => 'Python for Data Science',
            'content' => 'This module is focused on using Python for Data Science applications.',
        ]);
    }
}
