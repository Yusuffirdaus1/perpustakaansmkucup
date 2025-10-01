<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@smk65.test'],
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'admin@smk65.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'blacklist' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'test@example.com'],
            [
                'name' => 'Siswa Contoh',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'blacklist' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
