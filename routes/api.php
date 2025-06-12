<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ProgressController;


// Route untuk register & login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route yang memerlukan autentikasi dengan Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/courses', [CourseController::class, 'index']); // Menampilkan daftar kursus
     Route::post('/enroll/{course_id}', [EnrollmentController::class, 'enroll']); 
    Route::delete('/enroll/{course_id}', [EnrollmentController::class, 'unenroll']); 
    Route::get('/my-course', [MyCourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::delete('/enroll/{course_id}', [EnrollmentController::class, 'unenroll']); // Membatalkan enroll
    Route::put('/auth/user', [AuthController::class, 'updatePassword']);
    
Route::get('/lesson', [LessonController::class, 'index']);
    Route::post('/lesson/{lesson_id}/mark-complete', [LessonController::class, 'markComplete']); // Tandai pelajaran selesai
    Route::get('/lesson/{lesson_id}', [LessonController::class, 'show']); // Menampilkan detail pelajaran
    Route::get('/progress', [ProgressController::class, 'index']);
});
