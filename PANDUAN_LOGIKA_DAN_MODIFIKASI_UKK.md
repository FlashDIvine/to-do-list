# PANDUAN LOGIKA & MODIFIKASI KILAT UKK — TO-DO LIST LARAVEL

Stack proyek ini: **Laravel 13**, **Blade + Tailwind CSS (CDN)**, dan **SQLite (`database/database.sqlite`)**.

---

## 1. Workflow Penggunaan Aplikasi (Alur Pengguna dari A–Z)

### Tahap 1: Pengunjung / Tamu (`Guest`) Masuk ke Aplikasi
* **Buka URL `/login` atau `/register`**:
  * Rute ini dijaga oleh **Middleware `guest`** di `routes/web.php`.
  * **Analogi:** Satpam lobi hotel. Kalau kamu sudah punya kartu kamar (sudah login), satpam melarang kamu antre lagi di meja pendaftaran dan langsung mengarahkanmu masuk ke halaman utama (`/`).
* **Kirim Form `POST /register` atau `POST /login`**:
  * Data form dikirim ke `AuthController`.
  * Saat **Register (`AuthController@register`)**: input `name`, `email` (wajib unik), dan `password` (minimal 6 karakter + konfirmasi) divalidasi $\rightarrow$ password diacak satu arah dengan `Hash::make()` $\rightarrow$ user disimpan ke tabel `users` $\rightarrow$ otomatis login (`Auth::login($user)`).
  * Saat **Login (`AuthController@login`)**: email dan password dicocokkan lewat `Auth::attempt()`.
* **Dapat Tiket Sesi / Cookie Login**:
  * Begitu berhasil, server menjalankan `$request->session()->regenerate()` untuk mencetak ID sesi baru (mencegah *Session Fixation*).
  * File tiket sesi disimpan di `storage/framework/sessions/` (`SESSION_DRIVER=file`), sedangkan cookie pengenalnya disimpan di browser user $\rightarrow$ user diarahkan ke dashboard utama (`/`).

### Tahap 2: Manajemen Tugas di Dashboard (`GET /` $\rightarrow$ `route('tasks.index')`)
* **Melewati Middleware `auth`**:
  * Seluruh rute tugas (`GET /`, `POST /tasks`, `PUT /tasks/{id}`, `PATCH /tasks/{id}/toggle`, `PATCH /tasks/{id}/progress`, `DELETE /tasks/{id}`) dijaga **Middleware `auth`**. User yang belum login otomatis dilempar ke `/login`.
* **Ambil Data Terisolasi (Anti-IDOR)**:
  * `TaskController@index` mengambil data lewat `auth()->user()->tasks()->with('category')`, bukan `Task::all()`.
  * **Hasilnya:** User hanya melihat tugas miliknya sendiri, dan relasi kategori dimuat sekaligus lewat *Eager Loading* agar database tidak kerja bolak-balik (*N+1 Problem*).
* **Filter Dinamis**:
  * Pencarian judul (`search`), filter status (`pending`/`completed`), dan filter kategori (`category_id`) diproses memakai method Eloquent `when()`. Klausa `WHERE` hanya ditempel ke query jika input filternya benar-benar diisi.
  * Tugas diurutkan terbaru di atas via `latest()`.

### Tahap 3: Eksekusi Tugas (CRUD & Progres)
* **Tambah Tugas Baru (`POST /tasks` $\rightarrow$ `TaskController@store`)**:
  * Form wajib membawa `@csrf`. **Tidak ada** `<input type="hidden" name="user_id">` di HTML agar user tidak bisa memalsukan pemilik tugas lewat Inspect Element.
  * Server memvalidasi `title`, `description`, `priority` (`low`/`medium`/`high`), `due_date`, `category_id` (`exists:categories,id`), dan `progress` (`0–100`).
  * Data disimpan via `auth()->user()->tasks()->create($validated)` sehingga `user_id` otomatis diisi dari sesi login di server.
* **Toggle Status Selesai/Belum (`PATCH /tasks/{id}/toggle` $\rightarrow$ `TaskController@toggle`)**:
  * Saat checkbox diklik, `onchange="this.form.submit()"` langsung mengirim form `POST` yang disamarkan menjadi `PATCH` (`@method('PATCH')`).
  * Controller mengunci kepemilikan lewat `auth()->user()->tasks()->findOrFail($id)`.
  * Nilai `is_completed` dibalik dengan operator negasi `!$task->is_completed`, sekaligus menyinkronkan `progress` menjadi `100` (jika selesai) atau `0` (jika belum).
* **Slider & Tombol Bulat Progres (`PATCH /tasks/{id}/progress` $\rightarrow$ `TaskController@updateProgress`)**:
  * Menggeser slider atau klik tombol preset `25`, `50`, `75`, `100` memicu request `fetch()` AJAX ke server.
  * Jika nilai mencapai `100` (mentok kanan), `is_completed` otomatis diset `true`, judul tercoret (*line-through*), dan counter atas diperbarui live tanpa reload.
* **Edit Tugas (`PUT /tasks/{id}` $\rightarrow$ `TaskController@update`)**:
  * Klik ikon pensil membuka modal edit, validasi identik dengan `store()`, dan tetap memakai `auth()->user()->tasks()->findOrFail($id)`.
* **Hapus Tugas (`DELETE /tasks/{id}` $\rightarrow$ `TaskController@destroy`)**:
  * Tombol hapus diproteksi pop-up `onsubmit="return confirm(...)"`.
  * Jika disetujui, server mengecek kepemilikan via `findOrFail($id)` lalu mengeksekusi `$task->delete()` (**hard delete / hapus permanen** dari database SQLite).

### Tahap 4: Keluar Akun (`POST /logout`)
* Klik tombol **Keluar** di navbar $\rightarrow$ muncul konfirmasi `confirm('Apakah Anda yakin ingin keluar dari akun?')`.
* Jika OK, form `POST /logout` (dengan `@csrf`) masuk ke `AuthController@logout`:
  1. `Auth::logout()` — memutus status login.
  2. `$request->session()->invalidate()` — menghancurkan file sesi lama agar tidak bisa dibajak.
  3. `$request->session()->regenerateToken()` — memperbarui token CSRF untuk form berikutnya.
* User dikembalikan ke `/login` dengan pesan sukses.

---

## 2. Panduan Cepat Modifikasi Tampilan & Warna Input (Tailwind CSS)

### Aturan Emas Sebelum Edit
* **Buka file yang tepat:**
  * Halaman utama tugas, filter, badge, tombol, dan modal edit: `resources/views/tasks/index.blade.php`
  * Navbar, background halaman, logo, alert flash message, dan CSS slider: `resources/views/layouts/app.blade.php`
  * Form login & register: `resources/views/auth/login.blade.php` dan `resources/views/auth/register.blade.php`
* **Tidak perlu build ulang (`npm run build` / `npm run dev`):** Proyek ini memakai Tailwind CSS CDN (`https://cdn.tailwindcss.com`) di `layouts/app.blade.php`. Cukup edit file `.blade.php`, **Save**, lalu **Refresh browser (`F5`)**.

### A. Mengganti Warna Border & Ring Fokus Input Form
Di `resources/views/tasks/index.blade.php` (input Judul, Kategori, Prioritas, Tanggal, Deskripsi, Pencarian, dan Modal Edit), class default saat ini adalah:
```html
border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
```

Ganti bagian `border-*`, `focus:border-*`, dan `focus:ring-*` sesuai perintah penguji:
* **Ubah menjadi Biru:**
  ```html
  border border-blue-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
  ```
* **Ubah menjadi Hijau:**
  ```html
  border border-emerald-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
  ```
* **Ubah menjadi Merah:**
  ```html
  border border-red-300 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500
  ```
*(Tip cepat: gunakan `Ctrl + H` di editor untuk mengganti `focus:ring-indigo-500 focus:border-indigo-500` sekaligus di satu file).*

### B. Mengganti Warna Badge Prioritas & Badge Kategori
Lokasi di `resources/views/tasks/index.blade.php` (sekitar baris 206–224):
* **Badge Kategori (Default Indigo):**
  * Asli: `bg-indigo-50 text-indigo-700 border border-indigo-100`
  * Contoh ganti Ungu/Teal: `bg-teal-50 text-teal-700 border border-teal-100`
* **Badge High Priority (Default Merah):**
  * Asli: `bg-red-100 text-red-700 border border-red-200`
  * Contoh lebih tegas: `bg-red-600 text-white border border-red-700`
* **Badge Medium Priority (Default Kuning/Amber):**
  * Asli: `bg-amber-100 text-amber-700 border border-amber-200`
  * Contoh ganti Oranye: `bg-orange-100 text-orange-700 border border-orange-200`
* **Badge Low Priority (Default Abu-abu):**
  * Asli: `bg-slate-100 text-slate-700 border border-slate-200`
  * Contoh ganti Biru Muda: `bg-sky-100 text-sky-700 border border-sky-200`

### C. Mengubah Background Tombol Simpan, Tombol Hapus, & Banner Notifikasi
* **Tombol Simpan Tugas** (`tasks/index.blade.php` baris 105 & modal baris 431):
  * Default: `bg-indigo-600 hover:bg-indigo-700 text-white`
  * Ganti ke Biru/Hijau: `bg-blue-600 hover:bg-blue-700 text-white` atau `bg-emerald-600 hover:bg-emerald-700 text-white`
* **Tombol Hapus** (`tasks/index.blade.php` baris 258):
  * Default (ikon transparan merah saat hover): `text-slate-500 hover:text-rose-600 hover:bg-rose-50 ... border border-slate-200`
  * Jika penguji minta tombol merah solid: ganti menjadi `bg-red-600 hover:bg-red-700 text-white border border-red-600`
* **Banner Notifikasi Flash Message `session('success')`** (`resources/views/layouts/app.blade.php` baris 123–124):
  * Default (Hijau lembut):
    * Kotak: `bg-emerald-50 border border-emerald-200 text-emerald-800`
    * Ikon centang: `text-emerald-600`
  * Contoh ubah ke Biru lembut:
    * Kotak: `bg-blue-50 border border-blue-200 text-blue-800`
    * Ikon centang: `text-blue-600`
* **Catatan Khusus Warna Slider Progres:**
  * Jika penguji minta warna tema selain indigo, cek juga CSS `.modern-progress-slider` di `layouts/app.blade.php` (`#4f46e5` / `#10b981`) dan script `onSliderLiveChange` / `updateBulletButtons` di `tasks/index.blade.php`.

---

## 3. Kamus Kilat Konsep Kunci (Siap Jawab 10 Detik)

* **1. Apa itu `@csrf` dan apa fungsi parameter `_token`?**
  * **Jawaban Lisan Singkat:** `@csrf` adalah directive Blade yang otomatis membuat input tersembunyi bernama `_token` berisi kode acak unik milik sesi user. Fungsinya memastikan form `POST/PUT/PATCH/DELETE` benar-benar dikirim dari halaman website kita sendiri, bukan kiriman palsu dari situs lain. Tanpa `_token` valid, Laravel menolak request dengan error `419 Page Expired`.
  * **Analogi:** Stempel basah resmi di surat undangan. Surat tanpa stempel asli langsung ditolak satpam di pintu gerbang.

* **2. Kenapa mengambil tugas wajib lewat `auth()->user()->tasks()->...` dan apa bahayanya jika pakai `Task::find($id)`?**
  * **Jawaban Lisan Singkat:** `auth()->user()->tasks()` membatasi pencarian data **hanya** untuk milik user yang sedang login (*Scoped Query*). Jika memakai `Task::find($id)` langsung, user yang login bisa menebak angka ID di URL (misal ganti `/tasks/1` jadi `/tasks/2`) untuk melihat, mengubah, atau menghapus tugas milik user lain. Celah kebocoran akses ini disebut **IDOR** (*Insecure Direct Object Reference*).
  * **Analogi:** Mengambil paket di loker apartemen. `Task::find($id)` artinya membuka loker nomor berapa saja asal punya kuncinya; `auth()->user()->tasks()->findOrFail($id)` artinya satpam mengecek dulu apakah nomor loker itu memang atas namamu.

* **3. Apa fungsi `$fillable` di Model `User` dan `Task`, dan apa bahaya Mass Assignment?**
  * **Jawaban Lisan Singkat:** `$fillable` adalah daftar putih (*whitelist*) kolom tabel yang diizinkan untuk diisi secara massal lewat method `create()` atau `update()`. Bahaya *Mass Assignment* terjadi kalau attacker menyisipkan input ilegal lewat Inspect Element (misalnya `user_id` atau `role=admin`), lalu data sensitif itu ikut tersimpan ke database karena tidak dibatasi.
  * **Analogi:** Daftar barang bawaan yang boleh masuk kabin pesawat. Barang terlarang di luar daftar otomatis ditahan petugas bandara.

* **4. Mengapa di seeder kategori memakai `firstOrCreate` bukannya `create` biasa?**
  * **Jawaban Lisan Singkat:** `firstOrCreate` mengecek dulu ke database apakah kategori dengan nama tersebut sudah ada; kalau sudah ada tidak dibuat lagi, kalau belum baru dibuat. Jika memakai `create` biasa lalu seeder dijalankan dua kali (`php artisan db:seed`), data kategori seperti `"Sekolah"` akan tercatat ganda/duplikat.
  * **Analogi:** Mengecek absensi sebelum mencetak kartu nama. Kalau nama siswa sudah punya kartu, tidak perlu dicetak ulang dobel.

* **5. Apa beda method `up()` dan `down()` di file migration?**
  * **Jawaban Lisan Singkat:** Method `up()` dijalankan saat migrasi maju (`php artisan migrate`) untuk **membuat atau menambah** tabel/kolom di database. Method `down()` dijalankan saat rollback (`php artisan migrate:rollback`) untuk **membatalkan/menghapus** perubahan yang dilakukan `up()`.
  * **Analogi:** `up()` adalah tombol *Save/Apply* untuk membangun struktur rumah, sedangkan `down()` adalah tombol *Undo* (Ctrl+Z) untuk membongkar ulang struktur kalau ada revisi.

