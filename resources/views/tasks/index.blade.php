@extends('layouts.app', ['title' => 'Daftar Tugas Berbobot'])

@section('content')
<div class="space-y-8">

    <!-- Header & Quick Statistics -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Tugas</h1>
            <p class="text-sm text-slate-500 mt-1">Organisasi tugas berdasarkan prioritas dan kategori dengan standar keamanan tinggi.</p>
        </div>

        <!-- REASONING UKK: Menampilkan ringkasan status tugas untuk memberikan visual feedback cepat bagi penguji/pengguna -->
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-700" id="stat-total">
                Total: {{ $tasks->count() }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700" id="stat-completed">
                Selesai: {{ $tasks->where('is_completed', true)->count() }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700" id="stat-pending">
                Tertunda: {{ $tasks->where('is_completed', false)->count() }}
            </span>
        </div>
    </div>

    <!-- 1. Form Tambah Tugas Baru (Card Atas) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tambah Tugas Baru</h2>
            </div>
            <span class="text-xs text-slate-400 font-medium">* Wajib diisi</span>
        </div>

        <!-- REASONING UKK: DILARANG meletakkan input hidden user_id pada form! user_id diinjeksi di controller via auth()->user()->tasks() -->
        <form action="{{ route('tasks.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Judul Tugas -->
                <div class="md:col-span-6">
                    <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Judul Tugas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                        placeholder="Contoh: Menyelesaikan Modul Praktikum UKK"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400">
                </div>

                <!-- Kategori Tugas -->
                <div class="md:col-span-3">
                    <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Prioritas Tugas -->
                <div class="md:col-span-3">
                    <label for="priority" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Prioritas / Bobot <span class="text-rose-500">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Rendah)</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium (Sedang)</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Tinggi)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Tenggat Waktu (Due Date) -->
                <div class="md:col-span-4">
                    <label for="due_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tenggat Waktu (Opsional)
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white text-slate-700">
                </div>

                <!-- Deskripsi Tugas -->
                <div class="md:col-span-8">
                    <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi / Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="1"
                        placeholder="Rincian catatan teknis atau langkah pengerjaan..."
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center space-x-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition duration-150 shadow-md shadow-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Simpan Tugas</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Filter & Pencarian Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Pencarian Teks -->
            <div class="sm:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tugas..."
                    class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
            </div>

            <!-- Filter Status -->
            <div class="sm:col-span-3">
                <select name="status"
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Filter Kategori -->
            <div class="sm:col-span-3">
                <select name="category_id"
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Aksi Filter & Reset -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit"
                    class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition shadow-sm text-center">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'status', 'category_id']))
                    <!-- REASONING UKK: Tautan reset mengarahkan kembali ke route dasar tasks.index tanpa query string -->
                    <a href="{{ route('tasks.index') }}"
                        class="p-2 border border-slate-300 hover:bg-slate-100 text-slate-600 rounded-xl transition shadow-sm"
                        title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 3. Daftar Tugas (Task Cards / Rows) -->
    <div class="space-y-3">
        @forelse ($tasks as $task)
            <div id="task-card-{{ $task->id }}" class="bg-white rounded-2xl border {{ $task->is_completed ? 'border-slate-200 bg-slate-50/50' : 'border-slate-200 hover:border-indigo-200' }} p-4 sm:p-5 transition duration-150 shadow-sm flex flex-col gap-4">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <!-- Sisi Kiri: Checkbox, Judul, Kategori & Due Date -->
                    <div class="flex items-start space-x-3.5 flex-1 min-w-0">
                        <!-- REASONING UKK: Form PATCH otomatis terkirim saat checkbox di-klik menggunakan JavaScript onchange="this.form.submit()" -->
                        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="mt-1 flex-shrink-0">
                            @csrf
                            <!-- REASONING UKK: Method spoofing PATCH untuk operasi pembaruan parsial status boolean task -->
                            @method('PATCH')
                            <input type="checkbox" id="checkbox-{{ $task->id }}" onchange="this.form.submit()" {{ $task->is_completed ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 rounded-lg border-slate-300 focus:ring-indigo-500 cursor-pointer transition">
                        </form>

                        <div class="flex-1 min-w-0">
                            <!-- Judul Tugas -->
                            <div class="flex items-center space-x-2">
                                <h3 id="task-title-{{ $task->id }}" class="text-base {{ $task->is_completed ? 'line-through text-slate-400 font-medium' : 'text-slate-800 font-bold' }} truncate">
                                    {{ $task->title }}
                                </h3>
                            </div>

                            <!-- Deskripsi jika ada -->
                            @if ($task->description)
                                <p id="task-desc-{{ $task->id }}" class="text-xs {{ $task->is_completed ? 'line-through text-slate-300' : 'text-slate-500' }} mt-1 line-clamp-2">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <!-- Metadata Badges: Kategori, Prioritas, dan Tenggat Waktu -->
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <!-- Badge Kategori -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    📁 {{ $task->category->name }}
                                </span>

                                <!-- REASONING UKK: Indikator visual bobot prioritas (Merah = High, Kuning = Medium, Abu-abu = Low) -->
                                @if ($task->priority === 'high')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        🔥 High Priority
                                    </span>
                                @elseif ($task->priority === 'medium')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        ⚡ Medium Priority
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        🌱 Low Priority
                                    </span>
                                @endif

                                <!-- Tenggat Waktu (Due Date) -->
                                @if ($task->due_date)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium {{ !$task->is_completed && $task->due_date->isPast() ? 'bg-rose-50 text-rose-600 border border-rose-200 font-semibold' : 'bg-slate-100 text-slate-600' }}">
                                        📅 {{ $task->due_date->format('d M Y') }}
                                        @if (!$task->is_completed && $task->due_date->isPast())
                                            (Terlewat)
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Tombol Edit & Tombol Hapus -->
                    <div class="flex items-center space-x-2 self-end sm:self-center flex-shrink-0">
                        <!-- Tombol Edit Modal -->
                        <button type="button"
                            onclick="openEditModal({{ json_encode($task) }})"
                            class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition duration-150 border border-slate-200"
                            title="Ubah Tugas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>

                        <!-- Form Delete dengan Alert Konfirmasi -->
                        <!-- REASONING UKK: onsubmit return confirm() mencegah ketidaksengajaan penghapusan data secara interaktif di browser -->
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                            @csrf
                            <!-- REASONING UKK: Method spoofing DELETE untuk operasi hapus resource sesuai standar RESTful -->
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition duration-150 border border-slate-200"
                                title="Hapus Tugas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- REASONING UKK: Slider Progres Metalik Sesuai Gambar (Geser Mentok Kanan = Tugas Selesai) -->
                <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center space-x-2 text-xs">
                        <span class="font-bold text-slate-700">Tingkat Progres:</span>
                        <span id="progress-badge-{{ $task->id }}" class="font-bold px-2 py-0.5 rounded-md border text-xs {{ $task->is_completed ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ $task->progress ?? ($task->is_completed ? 100 : 0) }}%
                        </span>
                        <span id="progress-label-{{ $task->id }}" class="text-[11px] font-semibold {{ $task->is_completed ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $task->is_completed ? '✓ Selesai' : 'Geser mentok ke kanan untuk menyelesaikan' }}
                        </span>
                    </div>

                    <div class="w-full sm:w-72">
                        <div class="metallic-track-wrapper flex items-center space-x-2">
                            <span class="text-[10px] font-bold text-slate-500 pl-1 select-none">0%</span>
                            <div class="flex-1 relative flex items-center">
                                <input type="range" min="0" max="100" step="1"
                                    id="slider-{{ $task->id }}"
                                    value="{{ $task->progress ?? ($task->is_completed ? 100 : 0) }}"
                                    oninput="onSliderLiveChange({{ $task->id }}, this.value)"
                                    onchange="onSliderCommit({{ $task->id }}, this.value)"
                                    class="metallic-slider"
                                    title="Geser mentok kanan (100%) untuk menyelesaikan tugas">
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 pr-1 select-none">100%</span>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <!-- Tampilan Kosong (Empty State) -->
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-2xl text-slate-400 mb-4">
                    📝
                </div>
                <h3 class="text-base font-bold text-slate-700">Belum ada tugas ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    @if (request()->hasAny(['search', 'status', 'category_id']))
                        Tidak ada tugas yang cocok dengan kriteria pencarian/filter Anda. Silakan coba reset filter.
                    @else
                        Daftar tugas Anda masih kosong. Buat tugas baru pada formulir di atas untuk mulai produktif!
                    @endif
                </p>
            </div>
        @endforelse
    </div>

</div>

<!-- 4. Modal Edit Tugas (Pop-up Interaktif Tanpa Dependensi Berat) -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-lg w-full mx-4 overflow-hidden animate-scale-in">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Perbarui Tugas</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="editTaskForm" method="POST" class="p-6 space-y-4">
            @csrf
            <!-- REASONING UKK: Method spoofing PUT untuk memperbarui data utuh resource task -->
            @method('PUT')

            <div>
                <label for="edit_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Judul Tugas <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="edit_title" required
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit_category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="edit_category_id" required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_priority" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Prioritas <span class="text-rose-500">*</span>
                    </label>
                    <select name="priority" id="edit_priority" required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white">
                        <option value="low">Low (Rendah)</option>
                        <option value="medium">Medium (Sedang)</option>
                        <option value="high">High (Tinggi)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="edit_due_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Tenggat Waktu
                </label>
                <input type="date" name="due_date" id="edit_due_date"
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-white text-slate-700">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="edit_progress" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Progres Penyelesaian
                    </label>
                    <span id="edit_progress_val" class="text-xs font-extrabold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200">
                        0%
                    </span>
                </div>
                <input type="range" name="progress" id="edit_progress" min="0" max="100" step="1"
                    oninput="document.getElementById('edit_progress_val').innerText = this.value + '%'"
                    class="w-full accent-indigo-600 cursor-pointer">
                <span class="text-[11px] text-slate-400">Jika diset 100%, tugas otomatis ditandai selesai.</span>
            </div>

            <div>
                <label for="edit_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Deskripsi / Catatan Tambahan
                </label>
                <textarea name="description" id="edit_description" rows="3"
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"></textarea>
            </div>

            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-md shadow-indigo-100">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // REASONING UKK: Live feedback visual saat slider progres digeser (tanpa menunggu request database)
    function onSliderLiveChange(taskId, value) {
        const badge = document.getElementById(`progress-badge-${taskId}`);
        const label = document.getElementById(`progress-label-${taskId}`);
        const title = document.getElementById(`task-title-${taskId}`);
        const desc = document.getElementById(`task-desc-${taskId}`);
        const checkbox = document.getElementById(`checkbox-${taskId}`);
        const card = document.getElementById(`task-card-${taskId}`);

        if (badge) badge.innerText = `${value}%`;

        // REASONING UKK: Mentok kanan (100%) otomatis mengaktifkan status selesai (line-through dan badge hijau)
        if (parseInt(value) >= 100) {
            if (badge) badge.className = "font-bold px-2 py-0.5 rounded-md border text-xs bg-emerald-100 text-emerald-800 border-emerald-300";
            if (label) {
                label.className = "text-[11px] font-semibold text-emerald-600";
                label.innerText = "✓ Selesai";
            }
            if (title) title.className = "text-base line-through text-slate-400 font-medium truncate";
            if (desc) desc.className = "text-xs line-through text-slate-300 mt-1 line-clamp-2";
            if (checkbox) checkbox.checked = true;
            if (card) {
                card.classList.remove('hover:border-indigo-200');
                card.classList.add('bg-slate-50/50');
            }
        } else {
            if (badge) badge.className = "font-bold px-2 py-0.5 rounded-md border text-xs bg-slate-100 text-slate-700 border-slate-200";
            if (label) {
                label.className = "text-[11px] font-semibold text-slate-400";
                label.innerText = "Geser mentok ke kanan untuk menyelesaikan";
            }
            if (title) title.className = "text-base text-slate-800 font-bold truncate";
            if (desc) desc.className = "text-xs text-slate-500 mt-1 line-clamp-2";
            if (checkbox) checkbox.checked = false;
            if (card) {
                card.classList.remove('bg-slate-50/50');
                card.classList.add('hover:border-indigo-200');
            }
        }
    }

    // REASONING UKK: Eksekusi AJAX (Fetch API) saat slider dilepas untuk menyimpan nilai progres ke SQLite
    async function onSliderCommit(taskId, value) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/tasks/${taskId}/progress`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ progress: parseInt(value) })
            });

            const data = await response.json();
            if (data.success) {
                recalculateCounters();
            }
        } catch (error) {
            console.error('Gagal memperbarui progres:', error);
        }
    }

    // REASONING UKK: Hitung ulang indikator total selesai / tertunda secara dinamis di browser
    function recalculateCounters() {
        let completed = 0;
        let pending = 0;
        const sliders = document.querySelectorAll('.metallic-slider');

        sliders.forEach(slider => {
            if (parseInt(slider.value) >= 100) {
                completed++;
            } else {
                pending++;
            }
        });

        const statComp = document.getElementById('stat-completed');
        const statPend = document.getElementById('stat-pending');
        if (statComp) statComp.innerText = `Selesai: ${completed}`;
        if (statPend) statPend.innerText = `Tertunda: ${pending}`;
    }

    // REASONING UKK: Fungsi JavaScript murni tanpa dependency untuk mengisi value ke dalam modal edit
    function openEditModal(task) {
        const form = document.getElementById('editTaskForm');
        // REASONING UKK: Menyesuaikan action URL form ke rute tasks.update (/tasks/{id})
        form.action = `/tasks/${task.id}`;

        document.getElementById('edit_title').value = task.title;
        document.getElementById('edit_category_id').value = task.category_id;
        document.getElementById('edit_priority').value = task.priority;
        document.getElementById('edit_description').value = task.description || '';
        
        // Atur nilai slider progres di dalam modal
        const currentProgress = task.progress !== undefined && task.progress !== null ? task.progress : (task.is_completed ? 100 : 0);
        document.getElementById('edit_progress').value = currentProgress;
        document.getElementById('edit_progress_val').innerText = currentProgress + '%';

        // Format tanggal YYYY-MM-DD untuk input date HTML5
        if (task.due_date) {
            const dateStr = task.due_date.substring(0, 10);
            document.getElementById('edit_due_date').value = dateStr;
        } else {
            document.getElementById('edit_due_date').value = '';
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Menutup modal jika pengguna mengklik area luar modal (backdrop)
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    };
</script>
@endpush
@endsection

