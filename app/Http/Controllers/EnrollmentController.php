<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Course;  // Pastikan model Course di-import
use Illuminate\Database\QueryException;  // Untuk menangkap exception query

class EnrollmentController extends Controller
{
    // Fungsi untuk mendaftar ke kursus
    public function enroll($course_id)
    {
        try {
            $user = Auth::user();

            // Validasi apakah kursus dengan course_id tersebut ada
            $course = Course::find($course_id);
            if (!$course) {
                return response()->json([
                    'message' => 'Kursus tidak ditemukan.'
                ], 404); // Jika kursus tidak ditemukan
            }

            // Cek apakah user sudah terdaftar di kursus ini
            $existing = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $course_id)
                                  ->first();

            if ($existing) {
                // Jika sudah terdaftar, beri respons sukses dengan status 200 OK
                return response()->json([
                    'message' => 'Kamu sudah terdaftar di kursus ini.'
                ], 200); // Status code diubah menjadi 200 OK
            }

            // Simpan pendaftaran
            $enrollment = new Enrollment();
            $enrollment->user_id = $user->id;
            $enrollment->course_id = $course_id;
            $enrollment->is_enrolled = true; // pastikan kolom ini ada di tabel
            $enrollment->save();

            return response()->json([
                'message' => 'Berhasil mendaftar ke kursus.'
            ], 201); // Status code 201 Created untuk pendaftaran baru

        } catch (QueryException $e) {
            // Tangani kesalahan query dan tampilkan error yang terjadi
            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Fungsi untuk membatalkan pendaftaran
    public function unenroll($course_id)
    {
        try {
            $user = Auth::user();

            // Cek apakah user terdaftar di kursus ini
            $enrollment = Enrollment::where('user_id', $user->id)
                                    ->where('course_id', $course_id)
                                    ->first();

            if (!$enrollment) {
                return response()->json([
                    'message' => 'Kamu belum terdaftar di kursus ini.'
                ], 404); // Tidak ditemukan
            }

            // Hapus pendaftaran
            $enrollment->delete();

            return response()->json([
                'message' => 'Pendaftaran kursus telah dibatalkan.'
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
