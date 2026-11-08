<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // REASONING UKK: Whitelist atribut untuk proteksi Mass Assignment saat registrasi user
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // REASONING UKK: Menyembunyikan password dan remember_token dari array/JSON representation demi keamanan data
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // REASONING UKK: Otomatis melakukan hashing Bcrypt pada password saat disimpan atau di-assign
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi One-to-Many ke Task
     */
    // REASONING UKK: Relasi 1-ke-Banyak (One-to-Many); satu pengguna dapat memiliki banyak daftar tugas
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

