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
        $expiresAt = Carbon::now()->addMonth();

        return response()->json([
            'message' => 'Register berhasil',
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString(),
            'user' => $user
        ], 201);
    }

    // 📌 LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email atau password salah!'], 401);
        }

        $user = Auth::user();
        $loginAt = Carbon::now();

        $token = $user->createToken('auth_token', ['*'], Carbon::now()->addMonth())->plainTextToken;
        $expiresAt = Carbon::now()->addMonth();

        return response()->json([
            'message' => 'Login berhasil!',
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString(),
            'login_at' => $loginAt->toDateTimeString(),
            'user' => $user
        ]);
    }

    // 📌 LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil!']);
    }

    // 📌 UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $user = $request->user();

            // Validasi password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Password lama salah!'], 403);
            }

            // Update password baru
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password berhasil diperbarui!']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
