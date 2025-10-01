<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom sudah ada di create_users_table, jadi tidak perlu tambah lagi
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('role')->default('siswa');
        // });
    }

    public function down(): void
    {
        // Kolom sudah ada di create_users_table, jadi tidak perlu drop lagi
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('role');
        // });
    }
};
