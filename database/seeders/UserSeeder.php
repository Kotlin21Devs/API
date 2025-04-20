<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Dummy User',
            'email' => 'dummy@example.com',
            'password' => Hash::make('password123'), // password harus di-hash
        ]);

        // Tambah user lain kalau perlu
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('test1234'),
        ]);
    }
}
