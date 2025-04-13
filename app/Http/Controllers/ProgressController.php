<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\LessonProgress;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with(['course.modules.lessons', 'lessonProgresses.lesson'])
            ->where('user_id', $user->id)
            ->get();

        $progressList = $enrollments->map(function ($enrollment) {
            $allLessons = $enrollment->course->modules->flatMap->lessons;
            $totalLessons = $allLessons->count();

            $completedLessonIds = $enrollment->lessonProgresses->pluck('lesson_id')->toArray();
            $completedLessons = $allLessons->whereIn('id', $completedLessonIds);

            $lastLesson = $enrollment->lessonProgresses
                ->sortByDesc('updated_at')
                ->first();

            return [
                'course_id'    => $enrollment->course->id,
                'course_title' => $enrollment->course->title,
                'progress'     => $totalLessons > 0 ? round(count($completedLessons) / $totalLessons, 2) : 0,
                'last_lesson'  => $lastLesson?->lesson?->title ?? null,
                'updated_at'   => $lastLesson?->updated_at,
            ];
        });

        return response()->json($progressList);
    }
}
