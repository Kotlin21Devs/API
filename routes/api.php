<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ProgressController;

// Route untuk register & login (tanpa autentikasi)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route yang memerlukan autentikasi dengan Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/auth/user', [AuthController::class, 'updatePassword']);

    // Courses
    Route::get('/courses', [CourseController::class, 'index']); // Daftar semua kursus
    Route::get('/courses/{id}', [CourseController::class, 'show']); // Detail kursus

    // Enrollment
    Route::post('/enroll/{course_id}', [EnrollmentController::class, 'enroll']); // Enroll ke kursus
    Route::delete('/enroll/{course_id}', [EnrollmentController::class, 'unenroll']); // Batalkan enroll

    // My Courses
    Route::get('/my-courses', [MyCourseController::class, 'index']); // Daftar kursus yang diikuti

    // Lessons
    Route::get('/lessons', [LessonController::class, 'index']); // Daftar semua lesson (dikelompokkan per course)
    Route::get('/courses/{course_id}/lessons', [LessonController::class, 'byCourse']); // Daftar lesson untuk course tertentu
    Route::get('/lessons/{lesson_id}', [LessonController::class, 'show']); // Detail lesson
    Route::post('/lessons/{lesson_id}/mark-complete', [LessonController::class, 'markComplete']); // Tandai lesson selesai

    // Progress
    Route::get('/progress', [ProgressController::class, 'index']); // Progres user
});