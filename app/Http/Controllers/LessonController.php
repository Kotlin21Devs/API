<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function index()
    {
        // Ambil semua course dengan module dan lesson-nya
        $courses = Course::with(['modules' => function ($query) {
            $query->orderBy('order')->with(['lessons' => function ($query) {
                $query->orderBy('order');
            }]);
        }])->get();

        return response()->json([
            'message' => 'Semua pelajaran berhasil diambil.',
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'category' => $course->category,
                    'description' => $course->description,
                    'modules' => $course->modules->map(function ($module) {
                        return [
                            'id' => $module->id,
                            'course_id' => $module->course_id,
                            'title' => $module->title,
                            'content' => $module->content,
                            'order' => $module->order ?? 0,
                            'is_complete' => $module->is_complete,
                            'lessons' => $module->lessons->map(function ($lesson) {
                                return [
                                    'id' => $lesson->id,
                                    'course_id' => $lesson->course_id ?? $lesson->module->course_id,
                                    'module_id' => $lesson->module_id,
                                    'title' => $lesson->title,
                                    'content' => $lesson->content,
                                    'order' => $lesson->order,
                                ];
                            }),
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function byCourse($courseId)
    {
        // Pastikan course ada
        $course = Course::with(['modules' => function ($query) {
            $query->orderBy('order')->with(['lessons' => function ($query) {
                $query->orderBy('order');
            }]);
        }])->findOrFail($courseId);

        return response()->json([
            'message' => 'Pelajaran dari kursus berhasil diambil.',
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'category' => $course->category,
                'description' => $course->description,
            ],
            'modules' => $course->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'course_id' => $module->course_id,
                    'title' => $module->title,
                    'content' => $module->content,
                    'order' => $module->order ?? 0,
                    'is_complete' => $module->is_complete,
                    'lessons' => $module->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'course_id' => $lesson->course_id ?? $lesson->module->course_id,
                            'module_id' => $lesson->module_id,
                            'title' => $lesson->title,
                            'content' => $lesson->content,
                            'order' => $lesson->order,
                        ];
                    }),
                ];
            }),
        ]);
    }

    // Method markComplete dan show tetap sama
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

        $modules = Module::where('course_id', $course->id)
            ->with(['lessons' => function ($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return response()->json([
            'message' => 'Data pelajaran berhasil diambil.',
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'category' => $course->category,
                'description' => $course->description,
            ],
            'current_lesson' => [
                'id' => $lesson->id,
                'course_id' => $lesson->course_id ?? $course->id,
                'module_id' => $lesson->module_id,
                'title' => $lesson->title,
                'content' => $lesson->content,
                'order' => $lesson->order,
            ],
            'modules' => $modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'course_id' => $module->course_id,
                    'title' => $module->title,
                    'content' => $module->content,
                    'order' => $module->order ?? 0,
                    'is_complete' => $module->is_complete,
                    'lessons' => $module->lessons->map(function ($l) {
                        return [
                            'id' => $l->id,
                            'course_id' => $l->course_id ?? $l->module->course_id,
                            'module_id' => $l->module_id,
                            'title' => $l->title,
                            'content' => $l->content,
                            'order' => $l->order,
                        ];
                    }),
                ];
            }),
        ]);
    }
}