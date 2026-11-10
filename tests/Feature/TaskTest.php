<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // REASONING UKK: Menyiapkan data master kategori sebelum setiap pengujian dijalankan
        Category::create(['name' => 'Sekolah']);
        Category::create(['name' => 'Pribadi']);
    }

    /**
     * Uji coba: Pengguna yang belum login diarahkan ke halaman login saat membuka daftar tugas.
     */
    public function test_unauthenticated_user_cannot_access_tasks(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Uji coba: Pengguna yang login hanya dapat melihat tugas miliknya sendiri (Scoped Isolation).
     */
    public function test_user_can_only_see_their_own_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::first();

        // Buat task untuk User A dan User B
        $taskA = Task::create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
            'title' => 'Tugas Rahasia User A',
            'priority' => 'high',
        ]);

        $taskB = Task::create([
            'user_id' => $userB->id,
            'category_id' => $category->id,
            'title' => 'Tugas Rahasia User B',
            'priority' => 'low',
        ]);

        // Login sebagai User A
        $response = $this->actingAs($userA)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee('Tugas Rahasia User A');
        $response->assertDontSee('Tugas Rahasia User B');
    }

    /**
     * Uji coba: Proteksi Anti-IDOR pada operasi toggle status tugas orang lain.
     */
    public function test_user_cannot_toggle_other_users_task_anti_idor(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::first();

        $taskB = Task::create([
            'user_id' => $userB->id,
            'category_id' => $category->id,
            'title' => 'Tugas Milik B',
            'is_completed' => false,
            'priority' => 'medium',
        ]);

        // User A mencoba melakukan PATCH toggle pada task milik User B
        $response = $this->actingAs($userA)->patch(route('tasks.toggle', $taskB->id));

        // Harus mengembalikan HTTP 404 Not Found karena task tidak ditemukan di scope user A
        $response->assertStatus(404);
        $this->assertFalse($taskB->fresh()->is_completed);
    }

    /**
     * Uji coba: Pengguna dapat menambah tugas baru dengan data valid.
     */
    public function test_user_can_create_task_with_valid_data(): void
    {
        $user = User::factory()->create();
        $category = Category::first();

        $payload = [
            'title' => 'Mengerjakan Laporan UKK',
            'category_id' => $category->id,
            'priority' => 'high',
            'description' => 'Mempersiapkan materi live coding dan presentasi',
            'due_date' => now()->addDays(2)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->post(route('tasks.store'), $payload);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Mengerjakan Laporan UKK',
            'user_id' => $user->id,
            'priority' => 'high',
        ]);
    }

    /**
     * Uji coba: Slider progres dapat diperbarui dan jika mencapai 100% otomatis selesai.
     */
    public function test_user_can_update_task_progress_and_auto_complete_at_100(): void
    {
        $user = User::factory()->create();
        $category = Category::first();

        $task = Task::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Tugas dengan Slider',
            'priority' => 'medium',
            'progress' => 30,
            'is_completed' => false,
        ]);

        // Geser ke 75%
        $response = $this->actingAs($user)->patchJson(route('tasks.progress', $task->id), [
            'progress' => 75,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(75, $task->fresh()->progress);
        $this->assertFalse($task->fresh()->is_completed);

        // Geser mentok kanan (100%) -> harus otomatis selesai
        $response100 = $this->actingAs($user)->patchJson(route('tasks.progress', $task->id), [
            'progress' => 100,
        ]);

        $response100->assertStatus(200);
        $this->assertEquals(100, $task->fresh()->progress);
        $this->assertTrue($task->fresh()->is_completed);
    }

    /**
     * Uji coba: Proteksi Anti-IDOR pada slider progres tugas milik orang lain.
     */
    public function test_user_cannot_update_progress_of_other_users_task_anti_idor(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::first();

        $taskB = Task::create([
            'user_id' => $userB->id,
            'category_id' => $category->id,
            'title' => 'Tugas User B',
            'priority' => 'low',
            'progress' => 10,
        ]);

        // User A mencoba menggeser progres task milik User B
        $response = $this->actingAs($userA)->patchJson(route('tasks.progress', $taskB->id), [
            'progress' => 80,
        ]);

        $response->assertStatus(404);
        $this->assertEquals(10, $taskB->fresh()->progress);
    }
}

