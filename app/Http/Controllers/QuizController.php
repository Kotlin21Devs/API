<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function index($courseId)
    {
        try {
            $quizzes = Quiz::where('course_id', $courseId)
                ->where('user_id', Auth::id())
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $quizzes
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve quizzes: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quizzes'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Request data:', $request->all()); // Log data yang diterima

            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: User not authenticated'
                ], 401);
            }

            $options = [
                $request->input('option_a'),
                $request->input('option_b'),
                $request->input('option_c'),
                $request->input('option_d'),
            ];

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'question' => 'required|string',
                'option_a' => 'required|string|max:255',
                'option_b' => 'required|string|max:255',
                'option_c' => 'required|string|max:255',
                'option_d' => 'required|string|max:255',
                'correct_answer' => 'required|string|max:255|in:' . implode(',', array_map('strval', array_filter($options))),
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', ['errors' => $validator->errors()]);
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [
                'user_id' => Auth::id(),
                'course_id' => $request->course_id,
                'question' => $request->question,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_answer' => $request->correct_answer,
            ];

            Log::info('Data to be inserted:', $data); // Log data sebelum insert

            $quiz = Quiz::create($data);

            return response()->json([
                'status' => 'success',
                'data' => $quiz,
                'message' => 'Quiz created successfully'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create quiz: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $quiz = Quiz::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'data' => $quiz
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve quiz: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quiz'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $quiz = Quiz::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'course_id' => 'sometimes|exists:courses,id',
                'question' => 'sometimes|string',
                'option_a' => 'sometimes|string|max:255',
                'option_b' => 'sometimes|string|max:255',
                'option_c' => 'sometimes|string|max:255',
                'option_d' => 'sometimes|string|max:255',
                'correct_answer' => 'sometimes|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', ['errors' => $validator->errors()]);
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->has('correct_answer')) {
                $options = [
                    $request->input('option_a') ?? $quiz->option_a,
                    $request->input('option_b') ?? $quiz->option_b,
                    $request->input('option_c') ?? $quiz->option_c,
                    $request->input('option_d') ?? $quiz->option_d,
                ];
                if (!in_array($request->correct_answer, $options)) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => ['correct_answer' => 'The correct answer must match one of the options.']
                    ], 422);
                }
            }

            $quiz->update([
                'course_id' => $request->course_id ?? $quiz->course_id,
                'question' => $request->question ?? $quiz->question,
                'option_a' => $request->option_a ?? $quiz->option_a,
                'option_b' => $request->option_b ?? $quiz->option_b,
                'option_c' => $request->option_c ?? $quiz->option_c,
                'option_d' => $request->option_d ?? $quiz->option_d,
                'correct_answer' => $request->correct_answer ?? $quiz->correct_answer,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $quiz,
                'message' => 'Quiz updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update quiz: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update quiz'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $quiz = Quiz::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $quiz->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete quiz: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete quiz'
            ], 500);
        }
    }
    public function submitAnswer(Request $request, $courseId, $quizId)
{
    try {
        $validator = Validator::make($request->all(), [
            'answer' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
                'boolean' => false
            ], 422);
        }

        $quiz = Quiz::where('id', $quizId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        if ($request->answer === $quiz->correct_answer) {
            return response()->json([
                'message' => 'Jawaban benar',
                'boolean' => true
            ], 200);
        } else {
            return response()->json([
                'message' => 'Jawaban salah',
                'boolean' => false
            ], 200);
        }
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Quiz tidak ditemukan',
            'boolean' => false
        ], 404);
    } catch (\Exception $e) {
        Log::error('Gagal submit jawaban: ' . $e->getMessage());
        return response()->json([
            'message' => 'Gagal submit jawaban',
            'boolean' => false
        ], 500);
    }
}

}