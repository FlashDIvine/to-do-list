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
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        // REASONING UKK: Menentukan status selesai jika progres awal diset 100%
        if (isset($validated['progress']) && $validated['progress'] >= 100) {
            $validated['is_completed'] = true;
        }

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

        $newCompleted = !$task->is_completed;

        // REASONING UKK: Operator negasi (!) membalik nilai boolean dan sinkronkan progres (100 jika selesai, 0 jika belum)
        $task->update([
            'is_completed' => $newCompleted,
            'progress' => $newCompleted ? 100 : 0,
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
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        if (isset($validated['progress'])) {
            $validated['is_completed'] = ($validated['progress'] >= 100);
        }

        // REASONING UKK: Anti-IDOR mengunci akses pembaruan hanya untuk pemilik sah data task
        $task = auth()->user()->tasks()->findOrFail($id);

        // REASONING UKK: Eksekusi update data task yang telah tervalidasi
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Perbarui nilai slider progres tugas (0 - 100%).
     * Jika digeser mentok kanan (100%), tugas otomatis ditandai selesai.
     */
    public function updateProgress(Request $request, $id)
    {
        // REASONING UKK: Validasi rentang nilai slider dari 0 hingga 100 integer
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        // REASONING UKK: Anti-IDOR memastikan slider hanya dapat digeser oleh pemilik sah data task
        $task = auth()->user()->tasks()->findOrFail($id);

        // REASONING UKK: Jika slider mentok kanan (100%), status is_completed otomatis menjadi true (selesai)
        $isCompleted = ($validated['progress'] >= 100);

        $task->update([
            'progress' => $validated['progress'],
            'is_completed' => $isCompleted,
        ]);

        // REASONING UKK: Jika dipanggil melalui AJAX / Fetch API, kembalikan respons JSON agar antarmuka responsif tanpa reload
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'progress' => $task->progress,
                'is_completed' => $task->is_completed,
                'message' => $isCompleted ? 'Tugas selesai! (100%)' : 'Progres tugas diperbarui (' . $task->progress . '%)',
            ]);
        }

        return redirect()->back()->with('success', 'Progres tugas berhasil diperbarui!');
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

