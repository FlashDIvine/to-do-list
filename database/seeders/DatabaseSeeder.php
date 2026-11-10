<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;

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

        // REASONING UKK: Akun demo bawaan agar saat pengujian live coding, peserta tidak perlu repot registrasi manual
        $user = User::firstOrCreate(
            ['email' => 'peserta@ukk.sch.id'],
            [
                'name' => 'Peserta UKK RPL',
                'password' => Hash::make('password123'),
            ]
        );

        // REASONING UKK: Contoh data tugas awal berbobot untuk kebutuhan demo antarmuka
        $sekolah = Category::where('name', 'Sekolah')->first();
        $pribadi = Category::where('name', 'Pribadi')->first();

        if ($user->tasks()->count() === 0 && $sekolah && $pribadi) {
            $user->tasks()->create([
                'category_id' => $sekolah->id,
                'title' => 'Menyelesaikan Modul Live Coding UKK',
                'description' => 'Mempersiapkan materi presentasi dan demonstrasi fitur di depan penguji',
                'priority' => 'high',
                'progress' => 50,
                'due_date' => now()->addDays(3),
                'is_completed' => false,
            ]);

            $user->tasks()->create([
                'category_id' => $pribadi->id,
                'title' => 'Mempersiapkan Berkas Ujian',
                'description' => 'Mengecek kelengkapan kartu peserta dan alat tulis',
                'priority' => 'low',
                'progress' => 100,
                'due_date' => now()->addDays(1),
                'is_completed' => true,
            ]);
        }
    }
}

