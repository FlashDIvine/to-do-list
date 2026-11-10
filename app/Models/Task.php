<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // REASONING UKK: Menentukan kolom yang diizinkan untuk diisi via metode create() atau update() guna mencegah Mass Assignment Vulnerability
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'priority',
        'due_date',
        'progress',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // REASONING UKK: Type casting otomatis mengubah nilai database ke tipe data native PHP (boolean murni, integer, dan Carbon date instance)
    protected $casts = [
        'is_completed' => 'boolean',
        'progress' => 'integer',
        'due_date' => 'date',
    ];

    /**
     * Relasi Many-to-One ke User
     */
    // REASONING UKK: Inverse relation belongsTo; setiap tugas dimiliki secara eksklusif oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-One ke Category
     */
    // REASONING UKK: Inverse relation belongsTo; setiap tugas menginduk ke satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

