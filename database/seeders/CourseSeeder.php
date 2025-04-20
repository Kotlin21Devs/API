<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Course::create([
            'title' => 'Intro to Programming',
            'description' => 'Learn the basics of programming in various languages.',
            'category' => 'Basic',
        ]);

        Course::create([
            'title' => 'Advanced Web Development',
            'description' => 'Learn advanced web development techniques using modern frameworks.',
            'category' => 'Advanced',
        ]);

        Course::create([
            'title' => 'Data Science with Python',
            'description' => 'An introduction to data science and machine learning using Python.',
            'category' => 'Intermediate',
        ]);

        Course::create([
            'title' => 'Expert Level AI',
            'description' => 'Master artificial intelligence concepts and applications.',
            'category' => 'Expert',
        ]);
    }
}
