<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getQuizzes($course_id) { return Quiz::where('course_id', $course_id)->get(); }
    public function submitQuiz(Request $request) { /* Kode Submit Kuis */ }
}
