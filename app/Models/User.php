<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // WAJIB DITAMBAHKAN KARENA NAMA TABEL BUKAN 'users'
    protected $table = 'tb_user'; 

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'no_telepon',
        'level',
        'status',
        'is_verified',
        'is_banned',
        'last_activity_at'
    ];
    // Kolom yang disembunyikan saat data dikirim ke JSON/Mobile App
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'reset_token'
    ];

    // Casting tipe data otomatis
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_verified'       => 'boolean',
        'is_banned'         => 'boolean',
        'last_activity_at'  => 'datetime', // Tambahan: Agar terbaca sebagai objek Tanggal
    ];

    // Helper untuk cek Role
    public function isAdmin() { return $this->level === 'admin'; }
    public function isSeller() { return $this->level === 'seller'; }
    public function isCustomer() { return $this->level === 'customer'; }

    // Relasi ke Toko (User Seller punya Toko)
    public function toko()
    {
        return $this->hasOne(Toko::class, 'user_id', 'id');
    }
}