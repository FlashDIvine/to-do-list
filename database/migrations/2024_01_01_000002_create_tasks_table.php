<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // REASONING UKK: Foreign key ke users dengan onDelete cascade agar jika akun user dihapus, seluruh task miliknya terhapus otomatis demi integritas data
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // REASONING UKK: Foreign key ke categories dengan onDelete cascade untuk mengaitkan setiap task dengan data master kategori
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            // REASONING UKK: Kolom judul tugas dengan batas wajar 255 karakter
            $table->string('title', 255);
            // REASONING UKK: Kolom deskripsi bersifat opsional (nullable) untuk catatan detail tugas
            $table->text('description')->nullable();
            // REASONING UKK: Enum prioritas untuk klasifikasi bobot urgensi tugas (low, medium, high) dengan default 'medium'
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            // REASONING UKK: Tenggat waktu tugas bersifat opsional (nullable) bertipe date
            $table->date('due_date')->nullable();
            // REASONING UKK: Status penyelesaian tugas default bernilai false (belum selesai)
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

