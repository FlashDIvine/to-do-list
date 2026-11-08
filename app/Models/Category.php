<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // REASONING UKK: Whitelist kolom name agar bisa diisi secara mass-assignment dari seeder atau form
    protected $fillable = [
        'name',
    ];

    /**
     * Relasi One-to-Many ke Task
     */
    // REASONING UKK: Relasi 1-ke-Banyak; satu kategori dapat digunakan oleh banyak tugas (tasks)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

