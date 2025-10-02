<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Fungsi bantu redirect sesuai role
    private function redirectAfterAction()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect('/dashboard-petugas');
        } elseif ($user->role === 'siswa') {
            return redirect()->route('dashboard.siswa');
        }
        return redirect('/login');
    }

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
        return $this->redirectAfterAction()->with('success', 'Peminjaman berhasil!');
    }

    public function index()
    {
        $bukus = Buku::paginate(10);
        return view('peminjaman.index', compact('bukus'));
    }

    public function ajukan(Request $request, $buku_id)
    {
        $user = Auth::user();
        $buku = Buku::findOrFail($buku_id);

        if ($buku->jumlah < 1) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        Peminjaman::create([
            'user_id' => $user->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now(),
            'batas_pengembalian' => now()->addDays(7),
            'status' => 'menunggu',
        ]);

        $buku->jumlah -= 1;
        $buku->save();

        return $this->redirectAfterAction()->with('success', 'Pengajuan peminjaman berhasil!');
    }

    public function riwayat()
    {
        $user = Auth::user();
        $riwayat = Peminjaman::with('buku')->where('user_id', $user->id)->latest()->get();
        return view('peminjaman.riwayat', compact('riwayat'));
    }

    public function pengajuan()
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) abort(403);
        $pengajuan = Peminjaman::with(['user', 'buku'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();
        return view('peminjaman.pengajuan', compact('pengajuan'));
    }

    public function setujui($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) abort(403);
        $p = Peminjaman::findOrFail($id);
        if ($p->status === 'disetujui' && now()->gt(\Carbon\Carbon::parse($p->batas_pengembalian))) {
            $p->status = 'terlambat';
        } else {
            $p->status = 'disetujui';
        }
        $p->save();
        return $this->redirectAfterAction()->with('success', 'Peminjaman disetujui.');
    }

    public function tolak($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) abort(403);
        $p = Peminjaman::findOrFail($id);
        $p->status = 'ditolak';
        $p->save();
        $buku = $p->buku;
        $buku->jumlah += 1;
        $buku->save();
        return $this->redirectAfterAction()->with('success', 'Peminjaman ditolak.');
    }

    public function pengembalian()
    {
        $user = Auth::user();
        $pinjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'terlambat'])
            ->get();
        return view('peminjaman.pengembalian', compact('pinjamanAktif'));
    }

    public function kembalikan($id)
    {
        $p = Peminjaman::findOrFail($id);
        if ($p->user_id !== Auth::id()) abort(403);

        $p->status = 'menunggu_konfirmasi';
        $p->tanggal_kembali = now()->toDateTimeString();
        $p->save();

        return $this->redirectAfterAction()->with('success', 'Buku telah diajukan untuk pengembalian. Menunggu konfirmasi admin/petugas.');
    }

    public function konfirmasiPengembalian($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) abort(403);

        $p = Peminjaman::findOrFail($id);
        $p->status = 'selesai';
        $p->save();

        $buku = $p->buku;
        $buku->jumlah += 1;
        $buku->save();

        return $this->redirectAfterAction()->with('success', 'Pengembalian buku telah dikonfirmasi.');
    }

    public function daftarPengembalian()
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) abort(403);

        $pengembalian = Peminjaman::with(['user', 'buku'])
            ->where('status', 'menunggu_konfirmasi')
            ->latest()
            ->get();

        return view('peminjaman.daftar-pengembalian', compact('pengembalian'));
    }
}