<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    // <- Tambahkan baris ini:
    protected $table = 'peminjaman'; // default-nya Laravel kira tabelnya 'peminjamans'

    protected $fillable = [
        'user_id', 
        'buku_id', 
        'tanggal_pinjam', 
        'batas_pengembalian', 
        'status',
        'tanggal_kembali',
        'catatan_kembali'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'batas_pengembalian' => 'datetime',
        'tanggal_kembali' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
