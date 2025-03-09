<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

// Route untuk autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Gunakan middleware auth:sanctum agar hanya user yang sudah login bisa logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route untuk kursus
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Route untuk kuis
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/quizzes/{course_id}', [QuizController::class, 'getQuizzes']);
    Route::post('/quizzes/submit', [QuizController::class, 'submitQuiz']);
});

// Route untuk leaderboard (bisa diakses tanpa login)
Route::get('/leaderboard', [LeaderboardController::class, 'index']);

// Route untuk sertifikat, hanya user login yang bisa mengaksesnya
Route::middleware('auth:sanctum')->get('/certificates/{user_id}', [CertificateController::class, 'getCertificates']);
