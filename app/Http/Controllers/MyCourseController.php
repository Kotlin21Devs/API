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

        // Menyiapkan response kursus dan materi
        $response = [];
        foreach ($courses as $course) {
            // Ambil materi pertama (lesson pertama)
            $lesson = $course->modules->flatMap->lessons->first();
            $module = $lesson ? $lesson->module : null;

            // Menyiapkan nama file gambar berdasarkan ID kursus
            $thumbnailFilename = 'gambar' . $course->id . '.jpg';
            $thumbnailPath = public_path('images/thumbnails/' . $thumbnailFilename);

            // Memeriksa apakah gambar ada
            $thumbnailUrl = file_exists($thumbnailPath)
                ? asset('images/thumbnails/' . $thumbnailFilename)
                : asset('images/thumbnails/default.jpg');

            // Menambahkan data kursus ke dalam response
            $response[] = [
                'id' => $course->id,
                'title' => $course->title,
                'progress' => $progressMap[$course->id] ?? 0,
                'next_lesson' => $lesson ? [
                    'title' => $lesson->title,
                    'module' => $module ? $module->title : null,
                ] : null,
                'thumbnail_url' => $thumbnailUrl,
                'is_enrolled' => $enrolledCourseIds->contains($course->id),

                // ✅ Menyertakan modul dan lesson secara lengkap
                'modules' => $course->modules->map(function ($module) {
                    return [
                        'id' => $module->id,
                        'title' => $module->title,
                        'content' => $module->content,
                        'lessons' => $module->lessons->map(function ($lesson) {
                            return [
                                'id' => $lesson->id,
                                'title' => $lesson->title,
                                'content' => $lesson->content,
                            ];
                        }),
                    ];
                }),
            ];
        }

        return response()->json($response);
    }
}
