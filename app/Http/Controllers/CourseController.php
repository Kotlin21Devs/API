<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index() {
        return response()->json(Course::all());
    }

    public function show($id) {
        return response()->json(Course::findOrFail($id));
    }
}
