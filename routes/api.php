<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;

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

    // Module
    Route::post('/module/{module_id}/mark-complete', [CourseController::class, 'markComplete']); // Tandai module selesai

    // Progress
    Route::get('/progress', [ProgressController::class, 'index']); // Progres user

    // Quiz Routes
    Route::get('/courses/{course_id}/quizzes', [QuizController::class, 'index']); // Daftar kuis untuk kursus tertentu
    Route::post('/quizzes', [QuizController::class, 'store']); // Buat kuis baru
    Route::get('/quizzes/{id}', [QuizController::class, 'show']); // Detail kuis
    Route::put('/quizzes/{id}', [QuizController::class, 'update']); // Perbarui kuis
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']); // Hapus kuis
    Route::post('/courses/{courseId}/quizzes/{quizId}', [QuizController::class, 'submitAnswer']);
});