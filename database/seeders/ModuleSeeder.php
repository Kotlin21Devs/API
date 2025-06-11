<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
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

        Module::create([
            'course_id' => 1,
            'title' => 'Object-Oriented Programming',
            'content' => 'Learn about classes, objects, inheritance, and more in OOP.',
        ]);

        Module::create([
            'course_id' => 2,
            'title' => 'Frontend Development Basics',
            'content' => 'Covers HTML, CSS, and the foundations of responsive design.',
        ]);

        Module::create([
            'course_id' => 3,
            'title' => 'Database Fundamentals',
            'content' => 'Introduction to relational databases, SQL, and database design.',
        ]);
    }
}
