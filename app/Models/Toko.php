<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    // 1. Beritahu Laravel nama tabel aslinya di database Anda
    protected $table = 'tb_toko';

    // 2. Kolom yang TIDAK boleh diisi sembarangan (biasanya ID)
    // Sisanya (nama_toko, alamat, dll) otomatis boleh diisi
    protected $guarded = ['id'];

    // 3. Matikan timestamps jika tabel Anda tidak punya kolom 'updated_at' yang standar
    // (Tabel tb_toko Anda punya created_at & updated_at, jadi baris ini TIDAK PERLU ditulis,
    // biarkan default Laravel menyala)

    // ================= RELASI (Hubungan Antar Tabel) =================

    // Relasi: Toko ini milik siapa? (Milik User)
    // Di database: tb_toko ada kolom 'user_id'
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi: Toko ini punya apa? (Punya banyak Barang)
    // Di database: tb_barang ada kolom 'toko_id'
    public function barang()
    {
        return $this->hasMany(Barang::class, 'toko_id', 'id');
    }
}