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

<<<<<<< HEAD
    // WAJIB DITAMBAHKAN KARENA NAMA TABEL BUKAN 'users'
    protected $table = 'tb_user'; 

=======
    protected $table = 'tb_user'; // Benar, sesuai SQL Dump

    // === PERBAIKAN DI SINI ===
    // Tambahkan kolom yang akan sering di-update lewat Controller
>>>>>>> a08b632c2aa9fe6bcb487ef64029fa6676633682
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'no_telepon',
<<<<<<< HEAD
        'level',
        'status',
        'is_verified',
        'is_banned',
        'last_activity_at'
=======
        'jenis_kelamin',
        'alamat',
        'level', // admin, seller, customer
        'status', // online, offline, typing
        'is_verified',
        'is_banned',          // Tambahan: Agar admin bisa ban user
        'last_activity_at',   // Tambahan: Untuk fitur "Online Status" di Chat
        'profile_picture_url', // Tambahan: Untuk update foto profil
        'google_id'           // Tambahan: Untuk Login Google nanti
>>>>>>> a08b632c2aa9fe6bcb487ef64029fa6676633682
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