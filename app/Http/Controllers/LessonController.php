<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function markComplete($lessonId)
    {
        $user = Auth::user();

        // Ambil lesson beserta modul dan kursus
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;

        // Periksa apakah user sudah terdaftar di kursus
        $enrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (! $enrolled) {
            return response()->json([
                'message' => 'Anda belum terdaftar di kursus ini.'
            ], 403);
        }

        // Tandai pelajaran sebagai selesai
        $user->lessons()->syncWithoutDetaching([$lesson->id]);

        // Hitung progres berdasarkan total pelajaran dalam kursus
        $totalLessons = Lesson::whereHas('module', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->count();

        $completedLessons = $user->lessons()->whereHas('module', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->count();

        $progress = $totalLessons > 0 ? $completedLessons / $totalLessons : 0;

        return response()->json([
            'message' => 'Pelajaran ditandai selesai.',
            'updated_progress' => round($progress, 2)
        ]);
    }
}
