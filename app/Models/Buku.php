<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'sinopsis',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'jumlah_halaman',
        'genre',
        'jumlah',
        'gambar',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}