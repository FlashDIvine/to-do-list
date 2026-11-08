<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Tampilkan daftar tugas milik pengguna yang sedang login beserta filter dinamis.
     */
    public function index(Request $request)
    {
        // REASONING UKK: Scoped query via auth()->user()->tasks() memastikan isolasi data; user hanya mengambil data miliknya sendiri
        $tasks = auth()->user()->tasks()
            // REASONING UKK: Eager Loading dengan with('category') memecahkan N+1 Query Problem, memuat relasi kategori dalam 1 query efisien
            ->with('category')
            // REASONING UKK: Eloquent when() mengeksekusi filter pencarian judul secara kondisional hanya jika parameter search diisi oleh pengguna
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            // REASONING UKK: Eloquent when() memfilter status tugas (selesai/belum) secara dinamis tanpa if-else bersarang di luar query builder
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'completed') {
                    $query->where('is_completed', true);
                } elseif ($request->status === 'pending') {
                    $query->where('is_completed', false);
                }
            })
            // REASONING UKK: Eloquent when() menyaring tugas berdasarkan foreign key category_id yang dipilih
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            // REASONING UKK: Method latest() adalah shortcut dari orderBy('created_at', 'desc') agar tugas baru berada di urutan teratas
            ->latest()
            ->get();

        // REASONING UKK: Mengambil seluruh data master kategori yang diurutkan abjad untuk opsi dropdown formulir dan filter
        $categories = Category::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    /**
     * Simpan tugas baru yang dibuat oleh pengguna login.
     */
    public function store(Request $request)
    {
        // REASONING UKK: Validasi ketat di sisi server (Server-Side Validation) untuk integritas data dan proteksi input injection
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        // REASONING UKK: Simpan mass-assignment HANYA melalui relasi user; otomatis menginjeksi user_id dari sesi login dan mencegah manipulasi ID dari client-side
        auth()->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    /**
     * Balik status penyelesaian tugas (selesai <-> belum selesai).
     */
    public function toggle($id)
    {
        // REASONING UKK: Anti-IDOR (Insecure Direct Object Reference) memastikan task milik user aktif, otomatis melempar HTTP 404 jika task milik user lain
        $task = auth()->user()->tasks()->findOrFail($id);

        // REASONING UKK: Operator negasi (!) membalik nilai boolean secara atomik (true menjadi false, false menjadi true)
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui!');
    }

    /**
     * Perbarui data tugas yang ada.
     */
    public function update(Request $request, $id)
    {
        // REASONING UKK: Validasi input data update dengan aturan yang sama persis seperti store() untuk menjaga konsistensi data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        // REASONING UKK: Anti-IDOR mengunci akses pembaruan hanya untuk pemilik sah data task
        $task = auth()->user()->tasks()->findOrFail($id);

        // REASONING UKK: Eksekusi update data task yang telah tervalidasi
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Hapus tugas dari database.
     */
    public function destroy($id)
    {
        // REASONING UKK: Anti-IDOR mencegah pengguna menghapus tugas milik orang lain dengan membatasi query pencarian pada relasi user yang sedang login
        $task = auth()->user()->tasks()->findOrFail($id);

        // REASONING UKK: Menghapus rekaman task dari database secara permanen
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus!');
    }
}

