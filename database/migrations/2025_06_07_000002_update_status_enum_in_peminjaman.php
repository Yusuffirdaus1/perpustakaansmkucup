<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First convert enum to string to avoid MySQL limitation on altering enums
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status VARCHAR(255)");
        
        // Then convert back to enum with all possible values
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'menunggu_konfirmasi', 'selesai', 'terlambat') NOT NULL DEFAULT 'menunggu'");
    }

    public function down()
    {
        // Convert back to original enum
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam'");
    }
};
