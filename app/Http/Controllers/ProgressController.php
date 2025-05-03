<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class ProgressController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $enrollments = Enrollment::with([
                'course.modules.lessons',
                'lessonProgresses.lesson'
            ])->where('user_id', $user->id)->get();

            $progressList = $enrollments->map(function ($enrollment) {
                $course = $enrollment->course;

                // Skip jika course tidak tersedia
                if (!$course) return null;

                $modules = $course->modules ?? collect();

                $allLessons = $modules->flatMap(function ($module) {
                    return $module->lessons ?? collect();
                });

                $totalLessons = $allLessons->count();

                // Dapatkan ID lesson yang sudah selesai
                $completedLessonIds = $enrollment->lessonProgresses->pluck('lesson_id')->toArray();
                $completedLessons = $allLessons->whereIn('id', $completedLessonIds);

                // Ambil last lesson yang paling baru diakses
                $lastLessonProgress = $enrollment->lessonProgresses
                    ->sortByDesc('updated_at')
                    ->first();

                return [
                    'course_id'    => $course->id,
                    'course_title' => $course->title,
                    'progress'     => $totalLessons > 0 ? round($completedLessons->count() / $totalLessons, 2) : 0,
                    'last_lesson'  => optional(optional($lastLessonProgress)->lesson)->title,
                    'updated_at'   => optional($lastLessonProgress)->updated_at,
                ];
            })->filter()->values(); // buang null dan reset index

            return response()->json($progressList);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
