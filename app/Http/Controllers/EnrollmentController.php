<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function unenroll($course_id)
    {
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course_id)
                                ->first();

        if (!$enrollment) {
            return response()->json([
                'message' => 'Kamu belum terdaftar di kursus ini.'
            ], 404);
        }

        $enrollment->delete();

        return response()->json([
            'message' => 'Pendaftaran kursus telah dibatalkan.'
        ]);
    }
}
