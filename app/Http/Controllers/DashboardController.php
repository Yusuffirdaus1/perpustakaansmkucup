<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{    public function index()
    {
        // Jumlah total
        $totalBuku = Buku::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalPeminjamanAktif = Peminjaman::whereIn('status', ['disetujui', 'terlambat'])->count();
        $terlambat = Peminjaman::where('status', 'terlambat')->count();
        $menungguKonfirmasi = Peminjaman::where('status', 'menunggu_konfirmasi')->count();

        // Peminjaman terbaru (10 data terakhir)
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(10)
            ->get();        return view('dashboard', compact(
            'totalBuku',
            'totalSiswa',
            'totalPeminjamanAktif',
            'terlambat',
            'menungguKonfirmasi',
            'peminjamanTerbaru'
        ));
    }
}
