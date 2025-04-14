<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Module;

class CourseController extends Controller
{
    // Menampilkan daftar kursus
    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->query('search');

        $query = Course::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->with('modules.lessons')->get();

        $enrolledCourseIds = Enrollment::where('user_id', $user->id)->pluck('course_id');

        $progressMap = [];

        foreach ($enrolledCourseIds as $courseId) {
            $totalLessons = Lesson::whereIn('module_id', function ($q) use ($courseId) {
                $q->select('id')->from('modules')->where('course_id', $courseId);
            })->count();

            $completedLessons = $user->lessonProgress()->whereIn('lesson_id', function ($q) use ($courseId) {
                $q->select('lessons.id')
                  ->from('lessons')
                  ->join('modules', 'modules.id', '=', 'lessons.module_id')
                  ->where('modules.course_id', $courseId);
            })->count();

            $progressMap[$courseId] = $totalLessons > 0 ? round($completedLessons / $totalLessons, 2) : 0;
        }

        $result = $courses->map(function ($course) use ($enrolledCourseIds, $progressMap) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'thumbnail_url' => $course->thumbnail_url,
                'rating' => $course->rating,
                'is_enrolled' => $enrolledCourseIds->contains($course->id),
                'progress' => $enrolledCourseIds->contains($course->id) ? ($progressMap[$course->id] ?? 0) : null,
            ];
        });

        return response()->json($result);
    }

    // Endpoint: GET /api/my-course
    public function myCourse(Request $request)
    {
        $user = $request->user();

        $enrollment = Enrollment::with('course.modules.lessons')
            ->where('user_id', $user->id)
            ->get()
            ->sortBy(function ($enrollment) use ($user) {
                // Hitung progress
                $totalLessons = $enrollment->course->modules->flatMap->lessons->count();
                $completedLessons = $user->lessonProgress()
                    ->whereIn('lesson_id', $enrollment->course->modules->flatMap->lessons->pluck('id'))
                    ->count();
                $progress = $totalLessons > 0 ? $completedLessons / $totalLessons : 0;

                return $progress;
            })
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Tidak ada kursus aktif.'], 404);
        }

        $course = $enrollment->course;
        $nextLesson = null;

        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                $isCompleted = $user->lessonProgress()->where('lesson_id', $lesson->id)->exists();
                if (!$isCompleted) {
                    $nextLesson = [
                        'title' => $lesson->title,
                        'module' => $module->title,
                    ];
                    break 2;
                }
            }
        }

        $totalLessons = $course->modules->flatMap->lessons->count();
        $completedLessons = $user->lessonProgress()
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->count();
        $progress = $totalLessons > 0 ? round($completedLessons / $totalLessons, 2) : 0;

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'progress' => $progress,
            'next_lesson' => $nextLesson,
            'thumbnail_url' => $course->thumbnail_url, // Menambahkan thumbnail
        ]);
    }

    // Mendaftar kursus
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();
        $course_id = $request->course_id;

        $existingEnrollment = Enrollment::where('user_id', $user->id)
                                         ->where('course_id', $course_id)
                                         ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Sudah terdaftar di kursus ini.'], 409);
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
            'enrolled_at' => now(),
        ]);

        return response()->json(['message' => 'Pendaftaran kursus berhasil.']);
    }

    // Membatalkan kursus
    public function cancelEnrollment($course_id)
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course_id)
                                ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Pendaftaran tidak ditemukan.'], 404);
        }

        $enrollment->delete();

        return response()->json(['message' => 'Pendaftaran kursus dibatalkan.']);
    }

    public function show($id)
    {
        $course = Course::with('modules.lessons')->findOrFail($id);

        $response = [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'thumbnail_url' => $course->thumbnail_url, // Menambahkan thumbnail
            'modules' => $course->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'lessons' => $module->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'duration' => $lesson->duration ?? '0 menit', // pastikan field ini ada ya
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($response);
    }
}
