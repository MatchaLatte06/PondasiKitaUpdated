<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Penting untuk Mobile App nanti

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tb_user'; // Nama tabel Anda

    // Kolom yang boleh diisi
    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'no_telepon',
        'jenis_kelamin',
        'alamat',
        'level', // admin, seller, customer
        'status',
        'is_verified'
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
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // Helper untuk cek Role (Memudahkan coding nanti)
    public function isAdmin() { return $this->level === 'admin'; }
    public function isSeller() { return $this->level === 'seller'; }
    public function isCustomer() { return $this->level === 'customer'; }

    // Relasi ke Toko (User Seller punya Toko)
    public function toko()
    {
        return $this->hasOne(Toko::class, 'user_id', 'id');
    }
}