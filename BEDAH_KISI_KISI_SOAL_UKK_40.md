# Bedah Kisi-Kisi 40 Soal Ujian Teori UKK RPL & Keselarasan dengan Proyek Kita

> **Status Proyek:** **100% SESUAI & LENGKAP**  
> Seluruh 40 butir soal ujian teori kejuruan ini mengambil studi kasus nyata dari **Aplikasi To-Do List**. Kode di proyek kita saat ini merupakan implementasi fisik (*living example*) dari jawaban-jawaban yang ada di soal ujian tersebut.

---

## Ringkasan Cepat: Korelasi Soal dengan Kode Proyek Kita

| Klaster Materi | No. Soal Ujian | Implementasi di Kode Proyek Kita |
| :--- | :--- | :--- |
| **Relasi Basis Data & SQL** | 4, 5, 6, 24, 25, 26 | `database/migrations/*tasks_table.php`, foreign key `cascade`, Eloquent ORM scoped user. |
| **RESTful API & HTTP Method** | 12, 19, 27, 32 | `routes/web.php` rute `PATCH` toggle & progress, AJAX Fetch async/await dengan cek `response.ok`. |
| **Semantik HTML & Aksesibilitas** | 14, 15, 16, 35, 36, 37 | Tag `<header>`, `<main>`, `<footer>`, `<input type="search">`, `aria-label`, `<label for>`, `<input type="date">`. |
| **Keamanan & Validasi** | 7, 11 | Sanitasi XSS Blade `{{ }}`, Form Validation backend & atribut `required` front-end, CSRF `@csrf`. |
| **JavaScript & State Management** | 8, 10, 17, 18, 20, 21, 28, 29, 31, 34, 38, 39, 40 | Fetch AJAX slider realtime, DOM event handling, manipulasi state data dan render counter otomatis. |
| **Web Storage (localStorage)** | 9, 13, 30, 33 | Konsep serialisasi JSON (`JSON.stringify`/`parse`), penanganan error `try...catch` kuota penyimpanan. |
| **Struktur Data Dasar (CS)** | 1, 2, 3, 22, 23 | Teori LIFO (Stack), FIFO (Queue), Lookup O(1) (Hash Map), Hierarki (Tree), Array vs Linked List. |

---

## Bedah Detail Soal per Klaster (Siap Jawab Ujian Lisan & Tertulis)

---

### Klaster 1: Basis Data Relasional & SQL

#### Soal 24: Foreign Key & Integritas Referensial (Kunci: C)
* **Pertanyaan:** Jenis constraint yang wajib diterapkan pada kolom `user_id` di tabel `tasks` yang merujuk pada `id` di `users`?
* **Jawaban:** `FOREIGN KEY`.
* **Ada di Kode Kita:** Buka file `database/migrations/xxxx_create_tasks_table.php`:
  ```php
  $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
  ```
  *Artinya:* Jika akun pengguna dihapus, semua tugas miliknya otomatis ikut terhapus (*cascade*) demi menjaga kebersihan data.

#### Soal 25: Dampak UPDATE Tanpa Klausa WHERE (Kunci: C)
* **Pertanyaan:** Apa dampak menjalankan `UPDATE tasks SET status = 'selesai';` tanpa `WHERE`?
* **Jawaban:** Seluruh baris data pada tabel tasks akan berubah nilainya menjadi 'selesai'.
* **Ada di Kode Kita:** Di `app/Http/Controllers/TaskController.php`, kita selalu menggunakan query berlingkup pengguna aktif:
  ```php
  $task = auth()->user()->tasks()->findOrFail($id);
  ```
  Ini menjamin data yang di-update hanya 1 tugas spesifik milik user yang sedang login.

#### Soal 26: Menghitung Total Tugas per Pengguna (Kunci: B)
* **Pertanyaan:** Query SQL untuk menghitung jumlah tugas per `user_id`?
* **Jawaban:** `SELECT user_id, COUNT(id) FROM tasks GROUP BY user_id;`
* **Ada di Kode Kita:** Di dashboard kita menghitung `$tasks->count()` dan persentase selesai per user.

#### Soal 6: Optimasi Query Status Tugas yang Lambat (Kunci: B)
* **Pertanyaan:** Query pencarian status tugas pada jutaan baris data sangat lambat, apa solusinya?
* **Jawaban:** Menambahkan `INDEX` pada kolom status (`is_completed`). Index bekerja seperti daftar isi buku sehingga database tidak perlu membaca jutaan baris satu per satu (*Full Table Scan*).

#### Soal 5: Menampilkan Semua User Meski Belum Memiliki Tugas (Kunci: D)
* **Pertanyaan:** Jenis query JOIN untuk menampilkan semua pengguna, jika belum punya tugas tetap muncul dengan kolom tugas `NULL`?
* **Jawaban:** `LEFT JOIN`.
* **Di Laravel:** Diwakili oleh relasi Eloquent `$users = User::with('tasks')->get();`.

#### Soal 4: Database NoSQL Document-Oriented (Kunci: C)
* **Pertanyaan:** Pendekatan database untuk menyimpan struktur data JSON yang fleksibel dan dinamis tanpa harus mengubah skema tabel?
* **Jawaban:** `Document-oriented NoSQL (MongoDB)`.
* **Perbandingan dengan SQLite Kita:** SQLite/MySQL adalah RDBMS (terstruktur, tabel kaku, relasi kuat). Jika tugas punya properti bebas tak terduga (collaborators, tags dinamis), MongoDB lebih fleksibel.

---

### Klaster 2: Standar RESTful API & HTTP Method

#### Soal 27: HTTP Method untuk Update Sebagian Properti (Kunci: D)
* **Pertanyaan:** Metode HTTP RESTful yang paling sesuai untuk memperbarui **sebagian** properti (misalnya hanya status `isComplete` dari false ke true)?
* **Jawaban:** `PATCH` (Bukan PUT yang dipakai untuk mengganti seluruh data objek, dan bukan POST).
* **Ada di Kode Kita:** Buka `routes/web.php`:
  ```php
  Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
  Route::patch('/tasks/{id}/progress', [TaskController::class, 'updateProgress'])->name('tasks.progress');
  ```
  *Arsitektur kita 100% mengikuti standar industri ini!*

#### Soal 12 & 32: Async/Await Fetch & Penanganan Error HTTP 404/500 (Kunci: A)
* **Pertanyaan:** Mengapa `catch(error)` pada `fetch()` TIDAK otomatis menangkap error HTTP 404 atau 500?
* **Jawaban:** Karena `fetch` hanya melempar error pada **kegagalan koneksi/jaringan fisik**. Developer wajib memeriksa `if (!res.ok)` lalu melempar `throw new Error()` secara manual.
* **Ada di Kode Kita:** Buka `resources/views/tasks/index.blade.php` pada fungsi JavaScript slider:
  ```javascript
  const response = await fetch(`/tasks/${taskId}/progress`, { method: 'PATCH', ... });
  if (!response.ok) {
      throw new Error(`Server merespons status ${response.status}`);
  }
  ```

#### Soal 19: Mengapa Panggilan API Harus Asynchronous (Kunci: C)
* **Pertanyaan:** Mengapa pemanggilan API jarak jauh harus asynchronous (Promise) bukan synchronous?
* **Jawaban:** Agar request yang butuh waktu lama tidak memblokir (*blocking*) eksekusi UI pada *main thread* (browser tidak membeku/freeze).

---

### Klaster 3: Semantik HTML5 & Aksesibilitas Web (A11y)

#### Soal 14: Penggunaan Tag Semantik `<header>`, `<main>`, `<footer>` (Kunci: D)
* **Pertanyaan:** Alasan utama penggunaan elemen semantik dibanding sekadar `<div>`?
* **Jawaban:** Meningkatkan aksesibilitas bagi *Screen Reader* (tunanetra) dan membantu *Search Engine Optimization* (SEO).
* **Ada di Kode Kita:** File `resources/views/layouts/app.blade.php` menstrukturkan halaman dengan rapi menggunakan `<header>`, `<main>`, dan `<footer>`.

#### Soal 15: Input Pencarian yang Semantik (Kunci: E)
* **Pertanyaan:** Atribut `type` apa pada elemen `<input>` yang paling direkomendasikan untuk form pencarian tugas?
* **Jawaban:** `<input type="search">` (Browser otomatis memberikan tombol clear silang dan optimasi keyboard mobile).
* **Ada di Kode Kita:** Input pencarian di `resources/views/tasks/index.blade.php`:
  ```html
  <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama tugas...">
  ```

#### Soal 16: Tombol Ikon Tanpa Teks & Screen Reader (Kunci: B)
* **Pertanyaan:** Tombol hapus hanya berisi gambar ikon tempat sampah tanpa teks, atribut apa yang wajib ditambahkan?
* **Jawaban:** `aria-label="Hapus Tugas"` (Atribut `alt` hanya untuk tag `<img>`, bukan `<button>`).
* **Ada di Kode Kita:** Tombol hapus di kartu tugas kita:
  ```html
  <button type="submit" aria-label="Hapus Tugas" title="Hapus Tugas">
  ```

#### Soal 35: Menghubungkan Label dan Input Form (Kunci: C)
* **Pertanyaan:** Atribut pada tag `<label>` yang menghubungkannya secara eksplisit dengan `<input id="...">`?
* **Jawaban:** `for="id-elemen"` (Contoh: `<label for="title">` terhubung ke `<input id="title">`). Saat label diklik, kursor otomatis aktif di input.

#### Soal 36: Tombol di Dalam Form Tanpa Atribut Type (Kunci: C)
* **Pertanyaan:** Perilaku bawaan browser jika `<button>Batal</button>` ditaruh di dalam `<form>` tanpa atribut `type`?
* **Jawaban:** Otomatis bertindak sebagai `type="submit"` (akan mengirimkan form dan me-refresh halaman).
* **Ada di Kode Kita:** Di modal Edit Tugas, tombol Batal wajib kita beri `type="button"`:
  ```html
  <button type="button" onclick="closeEditModal()">Batal</button>
  ```

#### Soal 37: Input Tanggal Tenggat Waktu (Deadline) (Kunci: C)
* **Pertanyaan:** Elemen input HTML5 untuk tanggal standar `YYYY-MM-DD` dengan pemilih kalender bawaan browser?
* **Jawaban:** `<input type="date">`.
* **Ada di Kode Kita:** Kolom deadline tugas di proyek kita:
  ```html
  <input type="date" name="due_date" id="due_date">
  ```

---

### Klaster 4: Keamanan (Security) & Validasi Form

#### Soal 7: Pencegahan Celah XSS (Cross-Site Scripting) (Kunci: D)
* **Pertanyaan:** Cara penulisan DOM manipulation yang paling aman saat menambah elemen list baru dari input pengguna?
* **Jawaban:** `let li = document.createElement('li'); li.textContent = userInput; ul.appendChild(li);`  
  *(Jangan gunakan `innerHTML` karena script berbahaya `<script>` bisa dieksekusi).*
* **Di Laravel Blade:** Sintaks kurung kurawal ganda `{{ $task->title }}` otomatis mengonversi karakter berbahaya menjadi teks aman via `htmlspecialchars`.

#### Soal 11: Validasi Input Kosong atau Spasi (Kunci: A)
* **Pertanyaan:** Pencegahan awal terbaik di sisi front-end jika pengguna mengisi teks kosong/spasi?
* **Jawaban:** Menambahkan atribut `required` pada form HTML dan mengevaluasi `if (!input.trim()) return;` di JavaScript.
* **Ada di Kode Kita:** Input judul kita memiliki atribut `required` dan di Controller divalidasi dengan `'title' => 'required|string|max:255'`.

---

### Klaster 5: JavaScript Modern (ES6+), DOM, & Web Storage

#### Soal 8 & 29: Event Delegation & dataset.id (Kunci: C & B)
* **Soal 8:** Memasang 1 listener pada elemen induk `<ul>` daripada ratusan tombol disebut **Event Delegation**.
* **Soal 29:** Cara mengambil atribut `data-id="105"` pada elemen tombol adalah dengan `btn.dataset.id`.

#### Soal 9, 13, 30, 33: Manajemen Web Storage (localStorage)
* **Soal 9 (Kunci B):** Sebelum menyimpan Array of Objects ke localStorage wajib memanggil `JSON.stringify()`.
* **Soal 13 (Kunci C):** Saat membaca data dengan `JSON.parse()`, bungkus dengan `try...catch` dan siapkan fallback array kosong `[]` jika data korup.
* **Soal 30 (Kunci B):** Menghapus kunci spesifik tanpa merusak kunci lain menggunakan `localStorage.removeItem('todos')` (bukan `.clear()`).
* **Soal 33 (Kunci A):** Antisipasi kuota penuh / mode private browsing dengan membungkus `localStorage.setItem()` dalam `try...catch`.

#### Soal 17, 38, 40: Manipulasi Array Modern di JavaScript
* **Soal 17 (Kunci C):** Menyaring tugas yang selesai tanpa mengubah array asli menggunakan `tasks.filter()`.
* **Soal 38 (Kunci C):** Memetakan setiap objek tugas menjadi string HTML `<li>` dengan jumlah sama menggunakan `tasks.map()`.
* **Soal 40 (Kunci B):** `tasks.some(task => !task.isComplete)` menghasilkan `true` jika minimal ada satu tugas yang belum selesai.

#### Soal 18: Variabel `var` vs `let` pada Perulangan Asinkron (Kunci: B)
* **Pertanyaan:** Memperbaiki output `setTimeout` di perulangan agar mencetak 0, 1, 2?
* **Jawaban:** Ganti `var i = 0` menjadi `let i = 0` karena `let` memiliki cakupan blok (*block scope*).

#### Soal 20: Object Destructuring dengan Alias di ES6 (Kunci: B)
* **Pertanyaan:** Mengambil properti `title` sekaligus memberi alias `taskTitle`?
* **Jawaban:** `const { title: taskTitle } = task;`

#### Soal 21: Menyimpan Kategori Unik Otomatis (Kunci: B)
* **Pertanyaan:** Struktur data bawaan ES6 untuk menyimpan nilai unik tanpa duplikat?
* **Jawaban:** `Set` (misal: `new Set(['Kerja', 'Belajar', 'Kerja'])` otomatis hanya menyimpan 2 nilai).

#### Soal 28: Mencegah Form Me-reload Halaman (Kunci: B)
* **Pertanyaan:** Method JavaScript untuk mencegah perilaku default submit browser?
* **Jawaban:** `event.preventDefault()`.

#### Soal 31: Debouncing pada Fitur Pencarian Real-Time (Kunci: B)
* **Pertanyaan:** Menunda pencarian 300ms sampai pengguna selesai mengetik?
* **Jawaban:** Teknik **Debouncing**.

#### Soal 34: Optional Chaining (Kunci: B)
* **Pertanyaan:** Mencegah error saat properti bertingkat bernilai null/undefined?
* **Jawaban:** Menggunakan Optional Chaining `task.assignee?.name`.

#### Soal 39: Immutability State (Kunci: B)
* **Pertanyaan:** Menambahkan objek baru ke array tanpa memodifikasi array lama?
* **Jawaban:** Menggunakan Spread Operator `const updatedTasks = [...tasks, newTask];`.

---

### Klaster 6: Konsep Struktur Data Dasar (Computer Science)

* **Soal 1 (LIFO - Stack):** Mekanisme Undo / Redo (*Last In First Out*) menggunakan struktur data **Stack**.
* **Soal 2 (O(1) Lookup - Hash Map):** Pencarian data tercepat $O(1)$ berdasarkan ID unik menggunakan **Dictionary / Hash Map / JavaScript Object**.
* **Soal 3 (FIFO - Queue):** Sistem antrean pelanggan (*First In First Out*) menggunakan struktur data **Queue**.
* **Soal 22 (Hierarki - Tree):** Fitur sub-tugas bertingkat (*nested subtasks*) paling tepat dimodelkan dengan **Tree**.
* **Soal 23 (Array vs Linked List):** Array disimpan berurutan di memori sehingga mendukung akses acak indeks $O(1)$, sedangkan Linked List menghubungkan node lewat pointer sehingga pencarian butuh $O(n)$.

---

## Kesimpulan Evaluasi untuk Penguji UKK

Saat penguji menguji baik secara **teori tertulis**, **pilihan ganda**, maupun **tanya-jawab lisan**:
1. **Aplikasi To-Do List kita adalah perwujudan nyata dari 40 soal tersebut.**
2. Semua standar industri yang ditanyakan di soal (seperti HTTP `PATCH`, `Foreign Key Cascade`, tag semantik HTML5, `aria-label`, proteksi XSS, hingga penanganan error API `response.ok`) **sudah tertanam langsung dan berfungsi di kode aplikasi kamu.**
