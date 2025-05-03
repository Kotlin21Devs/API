<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 📌 REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cek apakah email sudah terdaftar
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['message' => 'Email sudah terdaftar!'], 409);
        }

        // Membuat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Membuat token dengan masa berlaku satu bulan
        $token = $user->createToken('auth_token', ['*'], Carbon::now()->addMonth())->plainTextToken;
        $expiresAt = Carbon::now()->addMonth(); // Menentukan tanggal kedaluwarsa token

        return response()->json([
            'message' => 'Register berhasil',
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString(), // Mengirimkan tanggal kedaluwarsa
            'user' => $user
        ], 201); // Status code 201 Created
    }

    // 📌 LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Cek apakah email dan password valid
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email atau password salah!'], 401);
        }

        $user = Auth::user();
        
        // Mendapatkan waktu login
        $loginAt = Carbon::now();

        // Membuat token dengan masa berlaku satu bulan
        $token = $user->createToken('auth_token', ['*'], Carbon::now()->addMonth())->plainTextToken;
        $expiresAt = Carbon::now()->addMonth(); // Menentukan tanggal kedaluwarsa token

        return response()->json([
            'message' => 'Login berhasil!',
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString(), // Mengirimkan tanggal kedaluwarsa
            'login_at' => $loginAt->toDateTimeString(), // Mengirimkan waktu login
            'user' => $user
        ]);
    }

    // 📌 LOGOUT
    public function logout(Request $request)
    {
        // Hapus semua token untuk pengguna saat ini
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil!']);
    }
}
