<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // REASONING UKK: Memanggil CategorySeeder agar saat php artisan migrate --seed dijalankan, data master kategori langsung terisi
        $this->call([
            CategorySeeder::class,
        ]);
    }
}

