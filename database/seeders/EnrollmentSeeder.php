<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        // Ambil kursus berdasarkan title yang ada di CourseSeeder
        $course1 = Course::where('title', 'Intro to Programming')->first();
        $course2 = Course::where('title', 'Advanced Web Development')->first();
        $course3 = Course::where('title', 'Data Science with Python')->first();
        $course4 = Course::where('title', 'Expert Level AI')->first();

        // Ambil user untuk di-enroll
        $user1 = User::find(1); // Pastikan user dengan ID 1 ada
        $user2 = User::find(2); // Pastikan user dengan ID 2 ada

        // Enroll user ke kursus yang relevan
        Enrollment::create([
            'user_id' => $user1->id,
            'course_id' => $course1->id,
            'enrolled_at' => Carbon::now(),
        ]);

        Enrollment::create([
            'user_id' => $user1->id,
            'course_id' => $course2->id,
            'enrolled_at' => Carbon::now()->subDays(2),
        ]);

        Enrollment::create([
            'user_id' => $user2->id,
            'course_id' => $course3->id,
            'enrolled_at' => Carbon::now()->subDay(),
        ]);

        Enrollment::create([
            'user_id' => $user2->id,
            'course_id' => $course4->id,
            'enrolled_at' => Carbon::now()->subDays(3),
        ]);
    }
}
