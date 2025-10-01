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
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu',
                'disetujui', 
                'ditolak', 
                'menunggu_konfirmasi', 
                'selesai', 
                'terlambat'
            ])->default('menunggu')->after('batas_pengembalian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])
                  ->default('dipinjam')
                  ->after('batas_pengembalian');
        });
    }
};
