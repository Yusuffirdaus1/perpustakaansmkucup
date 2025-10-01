<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|min:1900|max:' . date('Y'),
            'isbn' => 'required|string|max:50',
            'jumlah_halaman' => 'required|numeric|min:1',
            'genre' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }
        Buku::create($data);

        return redirect()->route('dashboard')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function index(Request $request)
    {
        $query = Buku::query();
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('pengarang', 'like', "%$search%")
                  ->orWhere('penerbit', 'like', "%$search%");
            });
        }
    
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }
    
        if ($request->filled('availability')) {
            if ($request->availability == 'available') {
                $query->where('jumlah', '>', 0);
            } elseif ($request->availability == 'unavailable') {
                $query->where('jumlah', '<=', 0);
            }
        }
    
        $bukus = $query->paginate(12)->withQueryString();
        $genres = Buku::select('genre')->distinct()->pluck('genre');
    
        return view('buku.index', compact('bukus', 'genres'));
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|min:1900|max:' . date('Y'),
            'isbn' => 'required|string|max:50',
            'jumlah_halaman' => 'required|numeric|min:1',
            'genre' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($buku->gambar) {
                Storage::disk('public')->delete($buku->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {        // Cek apakah buku sedang dipinjam
        if ($buku->peminjaman()->whereIn('status', ['menunggu', 'disetujui', 'terlambat'])->exists()) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Buku tidak dapat dihapus karena sedang dalam peminjaman.'
                ], 422);
            }
            return back()->with('error', 'Buku tidak dapat dihapus karena sedang dalam peminjaman.');
        }

        // Hapus gambar jika ada
        if ($buku->gambar) {
            Storage::disk('public')->delete($buku->gambar);
        }

        $buku->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Buku berhasil dihapus.'
            ]);
        }
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
