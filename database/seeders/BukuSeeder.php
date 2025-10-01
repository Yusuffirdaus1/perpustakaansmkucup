<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk menambahkan data ke tabel buku.
     */
    public function run(): void
    {
        Buku::create([
            'judul' => 'Laskar Pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'tahun_terbit' => 2005,
            'jumlah' => 10,
        ]);

        Buku::create([
            'judul' => 'Bumi',
            'pengarang' => 'Tere Liye',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2014,
            'jumlah' => 7,
        ]);

        Buku::create([
            'judul' => 'Negeri 5 Menara',
            'pengarang' => 'Ahmad Fuadi',
            'penerbit' => 'Gramedia Pustaka Utama',
            'tahun_terbit' => 2009,
            'jumlah' => 5,
        ]);
    }
}
