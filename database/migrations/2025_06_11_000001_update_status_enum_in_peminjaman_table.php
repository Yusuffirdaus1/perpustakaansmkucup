<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to force ENUM update for MySQL, now including 'selesai'
        \DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('menunggu','menunggu_konfirmasi','dipinjam','dikembalikan','terlambat','disetujui','ditolak','selesai') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous ENUM values (adjust as needed)
        \DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('menunggu','menunggu_konfirmasi','dipinjam','dikembalikan','terlambat','disetujui','ditolak') DEFAULT 'menunggu'");
    }
};
