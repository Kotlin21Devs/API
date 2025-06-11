<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function index()
    {
        // Ambil semua lesson tanpa memandang kursus
        $lessons = Lesson::with('module.course')->orderBy('order')->get();

        return response()->json([
            'message' => 'Semua pelajaran berhasil diambil.',
            'lessons' => $lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'content' => $lesson->content,
                    'order' => $lesson->order,
                    'module_id' => $lesson->module_id,
                    'course' => [
                        'id' => $lesson->module->course->id ?? null,
                        'title' => $lesson->module->course->title ?? null,
                    ]
                ];
            }),
        ]);
    }

    public function byCourse($courseId)
    {
        // Ambil semua lesson dari course tertentu
        $lessons = Lesson::whereHas('module', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with('module')->orderBy('order')->get();

        return response()->json([
            'message' => 'Pelajaran dari kursus berhasil diambil.',
            'course_id' => $courseId,
            'lessons' => $lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'content' => $lesson->content,
                    'order' => $lesson->order,
                    'module_id' => $lesson->module_id,
                ];
            }),
        ]);
    }

    public function markComplete($lessonId)
    {
        $user = Auth::user();

        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;

        $enrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$enrolled) {
            return response()->json([
                'message' => 'Anda belum terdaftar di kursus ini.'
            ], 403);
        }

        $user->lessons()->syncWithoutDetaching([$lesson->id]);

        $totalLessons = Lesson::whereHas('module', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->count();

        $completedLessons = $user->lessons()
            ->whereHas('module', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->count();

        $progress = $totalLessons > 0 ? $completedLessons / $totalLessons : 0;

        return response()->json([
            'message' => 'Pelajaran ditandai selesai.',
            'updated_progress' => round($progress, 2)
        ]);
    }

    public function show($lessonId)
    {
        $user = Auth::user();

        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;

        $enrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$enrolled) {
            return response()->json([
                'message' => 'Anda belum terdaftar di kursus ini.'
            ], 403);
        }

        $allLessons = Lesson::whereHas('module', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->orderBy('order')->get();

        return response()->json([
            'message' => 'Data pelajaran berhasil diambil.',
            'current_lesson' => [
                'id' => $lesson->id,
                'module_id' => $lesson->module_id,
                'title' => $lesson->title,
                'content' => $lesson->content,
                'order' => $lesson->order,
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'category' => $course->category,
                'description' => $course->description,
            ],
            'lessons_in_course' => $allLessons->map(function ($l) {
                return [
                    'id' => $l->id,
                    'module_id' => $l->module_id,
                    'title' => $l->title,
                    'content' => $l->content,
                    'order' => $l->order,
                ];
            }),
        ]);
    }
}
