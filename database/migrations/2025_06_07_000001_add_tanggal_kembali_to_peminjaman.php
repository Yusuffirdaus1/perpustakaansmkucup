<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalKembaliToPeminjaman extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->timestamp('tanggal_kembali')->nullable()->after('tanggal_pinjam');
            $table->string('catatan_kembali')->nullable()->after('tanggal_kembali');
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kembali', 'catatan_kembali']);
        });
    }
};
