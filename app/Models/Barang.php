<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // 1. Nama tabel di database
    protected $table = 'tb_barang';

    // 2. Lindungi ID, sisanya boleh diisi massal
    protected $guarded = ['id'];

    // ================= RELASI =================

    // Relasi: Barang ini dijual oleh siapa? (Oleh Toko)
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id', 'id');
    }

    // Relasi: Barang ini masuk kategori apa?
    // Asumsi Anda nanti buat model Kategori untuk tabel 'tb_kategori'
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    // Relasi: Barang ini gambarnya apa saja?
    // Asumsi Anda nanti buat model GambarBarang untuk tabel 'tb_gambar_barang'
    public function gambar()
    {
        return $this->hasMany(GambarBarang::class, 'barang_id', 'id');
    }
}