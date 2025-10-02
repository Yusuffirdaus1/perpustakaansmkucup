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

        // Petugas
        DB::table('users')->updateOrInsert(
            ['email' => 'petugas@smk65.test'],
            [
                'name' => 'Petugas Perpus',
                'password' => bcrypt('petugas123'),
                'role' => 'petugas',
            ]
        );
    }
}