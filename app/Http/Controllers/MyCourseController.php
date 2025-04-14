<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonProgress;
use App\Models\Course;
use App\Models\Enrollment;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil kursus yang sedang diikuti oleh user
        $enrolledCourseIds = Enrollment::where('user_id', $user->id)->pluck('course_id');
        $courses = Course::with('modules.lessons')->whereIn('id', $enrolledCourseIds)->get();

        if ($courses->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada kursus yang diikuti.',
            ], 404);
        }

        // Menghitung progress untuk setiap kursus
        $progressMap = [];
        foreach ($courses as $course) {
            $totalLessons = $course->modules->flatMap->lessons->count();
            $completedLessons = $user->lessonProgress()
                ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
                ->count();
            $progressMap[$course->id] = $totalLessons > 0 ? round($completedLessons / $totalLessons, 2) : 0;
        }

        // Mendapatkan kursus dengan progress terbaru
        $latestProgress = LessonProgress::with(['lesson.module.course'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestProgress) {
            return response()->json([
                'message' => 'Belum ada progress belajar',
            ], 404);
        }

        $lesson = $latestProgress->lesson;
        $module = $lesson->module;
        $course = $module->course;

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'progress' => $progressMap[$course->id] ?? 0,  // Menggunakan progress yang dihitung
            'next_lesson' => [
                'title' => $lesson->title,
                'module' => $module->title,
            ],
            'thumbnail_url' => $course->thumbnail_url,
            'is_enrolled' => $enrolledCourseIds->contains($course->id), // Menambahkan status enrollment
        ]);
    }
}
