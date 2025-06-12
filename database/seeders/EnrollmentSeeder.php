<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('email', 'dummy@example.com')->first();
        $user2 = User::where('email', 'test@example.com')->first();

        $course1 = Course::where('title', 'Pemrograman Dasar dengan Python')->first();
        $course2 = Course::where('title', 'JavaScript Lanjutan')->first();

        if ($user1 && $course1) {
            Enrollment::create([
                'user_id' => $user1->id,
                'course_id' => $course1->id,
                'enrolled_at' => Carbon::now(),
            ]);
            echo "✅ Enrolled: {$user1->email} → {$course1->title}\n";
        } else {
            echo "❌ Enrollment gagal untuk user1 atau course1\n";
        }

        if ($user2 && $course2) {
            Enrollment::create([
                'user_id' => $user2->id,
                'course_id' => $course2->id,
                'enrolled_at' => Carbon::now(),
            ]);
            echo "✅ Enrolled: {$user2->email} → {$course2->title}\n";
        } else {
            echo "❌ Enrollment gagal untuk user2 atau course2\n";
        }
    }
}
