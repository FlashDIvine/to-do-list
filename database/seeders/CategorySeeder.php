<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // REASONING UKK: Daftar kategori bawaan sistem untuk kebutuhan pengujian dan demo aplikasi
        $categories = [
            'Sekolah',
            'Pribadi',
            'Pekerjaan',
        ];

        foreach ($categories as $category) {
            // REASONING UKK: Menggunakan firstOrCreate untuk mencegah duplikasi data jika seeder dijalankan lebih dari sekali (idempotent)
            Category::firstOrCreate(['name' => $category]);
        }
    }
}

