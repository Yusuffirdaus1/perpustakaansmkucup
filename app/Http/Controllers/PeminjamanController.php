<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function create()
    {
        $bukus = Buku::where('jumlah', '>', 0)->get();
        return view('peminjaman.create', compact('bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'batas_pengembalian' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);
        $user = Auth::user();
        Peminjaman::create([
            'user_id' => $user->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'batas_pengembalian' => $request->batas_pengembalian,
            'status' => 'aktif',
        ]);
        // Kurangi stok buku
        $buku = Buku::find($request->buku_id);
        $buku->jumlah -= 1;
        $buku->save();
        return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil!');
    }    public function index()
    {
        // Daftar buku untuk peminjaman dengan pagination
        $bukus = \App\Models\Buku::paginate(10); // 10 buku per halaman
        return view('peminjaman.index', compact('bukus'));
    }

    public function ajukan(Request $request, $buku_id)
{
    $user = auth()->user();
    $buku = \App\Models\Buku::findOrFail($buku_id);

    // Cek stok
    if ($buku->jumlah < 1) {
        return back()->with('error', 'Stok buku tidak tersedia.');
    }

    // Simpan peminjaman
    \App\Models\Peminjaman::create([
        'user_id' => $user->id,
        'buku_id' => $buku->id,
        'tanggal_pinjam' => now(),
        'batas_pengembalian' => now()->addDays(7),
        'status' => 'menunggu',
    ]);

    // Kurangi stok buku
    $buku->jumlah -= 1;
    $buku->save();

    return redirect()->route('peminjaman.riwayat')->with('success', 'Pengajuan peminjaman berhasil!');
}

    public function riwayat()
    {
        $user = Auth::user();
        $riwayat = \App\Models\Peminjaman::with('buku')->where('user_id', $user->id)->latest()->get();
        return view('peminjaman.riwayat', compact('riwayat'));
    }

    public function pengajuan()
    {
        // Hanya admin yang boleh akses
        if (Auth::user()->role !== 'admin') abort(403);
        $pengajuan = \App\Models\Peminjaman::with(['user', 'buku'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();
        return view('peminjaman.pengajuan', compact('pengajuan'));
    }

    public function setujui($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $p = \App\Models\Peminjaman::findOrFail($id);
        // Jika sudah lewat batas pengembalian, status jadi 'terlambat'
        if ($p->status === 'disetujui' && now()->gt(\Carbon\Carbon::parse($p->batas_pengembalian))) {
            $p->status = 'terlambat';
        } else {
            $p->status = 'disetujui';
        }
        $p->save();
        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function tolak($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $p = \App\Models\Peminjaman::findOrFail($id);
        $p->status = 'ditolak';
        $p->save();
        // Kembalikan stok buku jika ditolak
        $buku = $p->buku;
        $buku->jumlah += 1;
        $buku->save();
        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function pengembalian()
    {
        $user = Auth::user();
        $pinjamanAktif = \App\Models\Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'terlambat'])
            ->get();
        return view('peminjaman.pengembalian', compact('pinjamanAktif'));
    }

    public function kembalikan($id)
    {
        $p = \App\Models\Peminjaman::findOrFail($id);
        if ($p->user_id !== Auth::id()) abort(403);
        
        // Update status menjadi 'menunggu_konfirmasi'
        $p->status = 'menunggu_konfirmasi';
        $p->tanggal_kembali = now()->toDateTimeString();
        $p->save();
        
        return back()->with('success', 'Buku telah diajukan untuk pengembalian. Menunggu konfirmasi admin.');
    }

    public function konfirmasiPengembalian($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $p = \App\Models\Peminjaman::findOrFail($id);
        $p->status = 'selesai';
        $p->save();
        
        // Tambah stok buku
        $buku = $p->buku;
        $buku->jumlah += 1;
        $buku->save();
        
        return back()->with('success', 'Pengembalian buku telah dikonfirmasi.');
    }

    public function daftarPengembalian()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $pengembalian = \App\Models\Peminjaman::with(['user', 'buku'])
            ->where('status', 'menunggu_konfirmasi')
            ->latest()
            ->get();
            
        return view('peminjaman.daftar-pengembalian', compact('pengembalian'));
    }
}
