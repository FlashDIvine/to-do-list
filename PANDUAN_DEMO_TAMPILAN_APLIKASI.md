# Panduan Presentasi & Demo Tampilan Aplikasi To-Do List Berbobot

Panduan ini disusun khusus untuk membantu kamu menjelaskan aplikasi kepada penguji atau audiens non-teknis dengan bahasa sehari-hari yang santai, jelas, dan fokus sepenuhnya pada apa yang terlihat dan dilakukan di layar.

---

## 1. Tur Tampilan Layar (Apa Saja yang Ada di Aplikasi)

### Halaman Masuk (Login) & Pendaftaran (Register)
Saat aplikasi pertama kali dibuka, layar menampilkan latar belakang abu-abu sangat lembut dengan bilah navigasi putih bersih di bagian paling atas.
* **Bilah Atas Halaman Tamu:** Di sebelah kiri terdapat kotak ikon centang berwarna biru keunguan dengan judul **TaskPriority** dan keterangan kecil **To-Do List Berbobot UKK RPL**. Di pojok kanan atas tersedia tautan **Masuk** dan tombol biru keunguan bertuliskan **Daftar**.
* **Kartu Formulir di Tengah Layar:**
  * Pada **Halaman Masuk**, terdapat satu kartu putih berbingkai halus dengan judul **Masuk ke Akun**. Di dalamnya tersusun kotak isian **Alamat Email**, kotak isian **Kata Sandi**, kotak centang kecil **Ingat saya**, serta tombol lebar berwarna biru keunguan bertuliskan **Masuk Sekarang**. Di bagian paling bawah kartu tersedia tautan **Daftar Akun Baru** untuk berpindah ke halaman pendaftaran.
  * Pada **Halaman Pendaftaran**, tata letaknya serupa dengan judul **Daftar Akun Baru**. Di dalamnya tersedia empat kotak isian berurutan yaitu **Nama Lengkap**, **Alamat Email**, **Kata Sandi (Min. 6 Karakter)**, dan **Konfirmasi Kata Sandi**, diikuti tombol lebar bertuliskan **Daftarkan Akun** serta tautan **Masuk di Sini** di bagian bawah untuk kembali ke halaman masuk.

### Halaman Utama (Dashboard Daftar Tugas)
Setelah berhasil masuk, pengguna langsung melihat ruang kerja utama yang tersusun rapi dari atas ke bawah:
1. **Bagian Atas (Header & Ringkasan):**
   * Di bilah navigasi paling atas sebelah kanan, tampil nama lengkap dan alamat email pengguna yang sedang aktif, diikuti tombol putih berbingkai tipis bertuliskan **Keluar**.
   * Di bawah bilah navigasi terdapat judul halaman besar **Manajemen Tugas**, dan di sebelah kanannya terdapat tiga lencana kapsul penghitung status: **Total** (abu-abu), **Selesai** (hijau lembut), dan **Tertunda** (kuning lembut).
2. **Bagian Formulir Tambah Tugas:**
   * Berupa kartu putih bertajuk **Tambah Tugas Baru** dengan titik penanda biru keunguan dan catatan bahwa kolom bertanda bintang merah wajib diisi.
   * Baris pertama berisi tiga kolom sejajar: kotak ketik **Judul Tugas**, pilihan dropdown **Kategori**, dan pilihan dropdown **Prioritas / Bobot** (*Low / Rendah*, *Medium / Sedang*, *High / Tinggi*).
   * Baris kedua berisi dua kolom sejajar: pemilih tanggal kalender **Tenggat Waktu (Opsional)** dan kolom ketik **Deskripsi / Catatan Tambahan (Opsional)**.
   * Di pojok kanan bawah kartu terdapat tombol solid berwarna biru keunguan bertuliskan **Simpan Tugas**.
3. **Bagian Pencarian & Penyaringan (Filter):**
   * Tepat di bawah formulir tambah tugas terdapat satu bilah ramping berisi empat elemen sejajar: kotak pencarian berikon kaca pembesar bertuliskan *Cari nama tugas...*, pilihan dropdown status (*Semua Status*, *Belum Selesai*, *Selesai*), pilihan dropdown kategori (*Semua Kategori* serta pilihan seperti *Sekolah*, *Pribadi*, dan *Pekerjaan*), serta tombol berwarna gelap bertuliskan **Filter**. Jika penyaringan sedang dipakai, muncul tombol kecil berikon silang di sebelahnya untuk mengembalikan semua daftar.
4. **Bagian Daftar Kartu Tugas:**
   * Semua tugas ditampilkan sebagai kartu putih terpisah yang tersusun dari atas ke bawah (tugas terbaru berada paling atas).
   * Di bagian atas tiap kartu terdapat kotak centang di sisi kiri, judul tugas dan deskripsi di tengah, barisan label warna untuk **Kategori**, **Prioritas**, dan **Tenggat Waktu**, serta dua tombol ikon di sisi kanan yakni ikon **Pensil** (ubah tugas) dan ikon **Tempat Sampah** (hapus tugas).
   * Di bagian bawah tiap kartu terdapat bilah **Tingkat Progres** yang memuat angka persentase, garis geser interaktif, dan empat tombol bulat pilihan cepat bertuliskan **25**, **50**, **75**, dan **100**.
   * Jika daftar tugas kosong atau hasil pencarian tidak cocok, layar menampilkan kotak bingkai garis putus-putus dengan ikon buku catatan bertuliskan *Belum ada tugas ditemukan*.

---

## 2. Alur Penggunaan Aplikasi (Workflow Pengguna dari Awal sampai Selesai)

### Langkah 1 – Masuk ke Ruang Kerja Pribadi
Pengguna baru membuat akun melalui halaman pendaftaran, sedangkan pengguna yang sudah memiliki akun cukup mengetikkan email dan kata sandi di halaman masuk. Begitu berhasil masuk, muncul banner pesan hijau di bagian atas dan pengguna langsung berada di ruang kerja pribadinya. Setiap pengguna memiliki ruang kerja yang terpisah, sehingga daftar tugas yang dibuat oleh satu akun hanya terlihat oleh pemilik akun itu sendiri dan tidak pernah tercampur dengan tugas milik pengguna lain.

### Langkah 2 – Mencatat Tugas Baru
Pengguna mengisi formulir di bagian atas halaman utama mulai dari judul tugas, memilih kategori (misalnya *Sekolah*), menentukan tingkat kepentingannya (*High*, *Medium*, atau *Low*), memilih tanggal tenggat jika ada, lalu menambahkan deskripsi singkat. Setelah tombol **Simpan Tugas** ditekan, layar langsung memunculkan banner pesan berhasil berwarna hijau di bagian atas, jumlah hitungan tugas bertambah, dan kartu tugas baru langsung muncul di urutan paling atas.

### Langkah 3 – Menandai Tugas Selesai & Mengatur Progres
Pengguna dapat memperbarui kemajuan tugas melalui dua cara yang sangat cepat:
* **Cara Pertama (Tombol Centang):** Pengguna mengklik kotak centang di sebelah kiri judul tugas. Seketika status tugas berubah menjadi selesai dengan progres penuh **100%**, judul serta deskripsinya tercoret garis tengah dan memudar menjadi abu-abu, dan banner pesan hijau muncul di bagian atas. Jika kotak centang diklik kembali, tugas langsung kembali ke status belum selesai (**0%**).
* **Cara Kedua (Garis Geser & Tombol Bulat):** Pengguna bisa menekan tombol bulat **25**, **50**, **75**, atau **100**, maupun menggeser titik pada garis progres. Apabila digeser mentok ke kanan hingga **100%** atau tombol bulat **100** diklik, tugas otomatis langsung berstatus **Selesai**, judul tercoret, kotak centang terisi, dan angka penghitung **Selesai** di bagian atas halaman ikut bertambah seketika.

### Langkah 4 – Mencari, Mengubah, dan Menghapus Tugas
* **Mencari & Menyaring:** Ketika tugas sudah banyak, pengguna dapat mengetik kata kunci di kotak pencarian, memilih status atau kategori tertentu, lalu menekan tombol **Filter** agar daftar langsung menyusut sesuai pilihan. Tombol silang di sebelah kanan bisa ditekan kapan saja untuk membersihkan penyaringan.
* **Mengubah Isi Tugas:** Pengguna menekan tombol ikon **Pensil** pada kartu yang ingin diperbaiki. Jendela pop-up **Perbarui Tugas** akan muncul di tengah layar sehingga pengguna dapat mengubah judul, kategori, prioritas, tanggal tenggat, penggeser progres, maupun deskripsi, lalu menyimpannya dengan menekan tombol **Simpan Perubahan**.
* **Menghapus Tugas:** Pengguna menekan tombol ikon **Tempat Sampah**. Untuk mencegah penghapusan yang tidak disengaja, layar terlebih dahulu memunculkan kotak dialog konfirmasi bertuliskan *"Apakah Anda yakin ingin menghapus tugas ini?"*. Jika pengguna memilih **OK**, barulah tugas terhapus dari daftar disertai munculnya banner pesan hijau.

### Langkah 5 – Keluar Akun
Saat pekerjaan selesai, pengguna menekan tombol **Keluar** di pojok kanan atas. Kotak dialog konfirmasi akan muncul terlebih dahulu untuk memastikan pengguna benar-benar ingin keluar. Setelah dikonfirmasi, ruang kerja langsung dikunci dan layar kembali ke halaman Masuk dengan pesan hijau bahwa akun telah berhasil keluar.

---

## 3. Anatomi Visual & Interaksi Warna pada Tampilan

### Reaksi Kotak Input Saat Digunakan
* **Saat Diklik atau Aktif Mengetik:** Dalam keadaan normal, setiap kotak isian berbingkai abu-abu muda yang tenang. Begitu pengguna mengklik salah satu kotak isian, garis tepinya seketika menyala menjadi **biru keunguan tegas disertai cincin sorotan cahaya lembut di sekeliling kotak**, sehingga mata pengguna langsung tahu persis di kolom mana ia sedang mengetik.
* **Saat Kolom Wajib Lupa Diisi atau Keliru:** Jika pengguna menekan tombol simpan dalam keadaan kolom wajib masih kosong, peramban langsung menahan pengiriman dan mengarahkan kursor ke kotak yang kosong tersebut dengan balon peringatan. Apabila ada isian yang ditolak saat dikirim (misalnya kata sandi pendaftaran kurang dari 6 karakter atau email salah saat masuk), muncul banner peringatan **berwarna merah muda tegas berikon tanda seru** di bagian atas halaman yang menjelaskan kesalahannya secara rinci.

### Arti Kode Warna Label & Tombol
* **Kode Warna Label Prioritas (Memudahkan Mata Memilah Kepentingan):**
  * **Merah berikon api (High Priority):** Menandakan tugas paling mendesak atau penting sekali agar langsung tertangkap mata terlebih dahulu.
  * **Kuning keemasan berikon petir (Medium Priority):** Menandakan tugas tingkat menengah yang perlu dikerjakan secara terjadwal.
  * **Abu-abu sejuk berikon tunas hijau (Low Priority):** Menandakan tugas ringan atau yang tenggat waktunya masih longgar.
  * *(Sebagai penanda tambahan yang sangat membantu, label **Tenggat Waktu** akan berubah menjadi **merah dengan tambahan kata Terlewat** apabila tanggal batas sudah lewat tetapi tugas belum selesai.)*
* **Warna Label Kategori:** Memakai latar **biru keunguan lembut berikon folder** agar terlihat jelas sebagai pengelompokan tempat tugas (*Sekolah*, *Pribadi*, atau *Pekerjaan*) tanpa bertabrakan dengan warna prioritas.
* **Kontras Tombol Utama vs Tombol Berbahaya:**
  * Tombol aksi utama yang aman (**Simpan Tugas**, **Simpan Perubahan**, **Masuk Sekarang**, **Daftarkan Akun**) memakai warna solid **biru keunguan cerah** supaya menonjol dan mengundang pengguna menekan tombol tersebut.
  * Tombol **Filter** memakai warna **abu-abu gelap arang** agar tetap tegas namun tidak menyaingi tombol simpan utama.
  * Tombol aksi sensitif (**Hapus** berikon tempat sampah dan **Keluar**) dirancang putih bersih saat diam agar tampilan tidak penuh warna merah yang menakutkan, lalu langsung berubah **merah** ketika diarahkan kursor sekaligus dilindungi kotak dialog konfirmasi saat diklik.

### Peta Perubahan Warna Cepat (Tanpa Kode)
Jika penguji atau pemilik aplikasi ingin mengubah nuansa warna tampilan (misalnya dari tema biru keunguan saat ini menjadi tema biru laut, hijau segar, atau oranye hangat), berikut area visual yang bisa diganti beserta dampaknya bagi pengguna:
1. **Ikon Logo Aplikasi & Tombol Aksi Utama:** Mengubah warna kotak centang di pojok kiri atas beserta tombol **Simpan Tugas**, **Simpan Perubahan**, dan tombol masuk/daftar agar seluruh identitas visual utama langsung berganti nuansa (misalnya menjadi biru laut atau hijau zamrud).
2. **Garis Sorotan Kotak Isian Saat Aktif:** Menyamakan warna bingkai nyala dan sorotan cahaya kotak ketik saat diklik dengan warna tema baru agar terasa selaras saat mengetik.
3. **Garis Geser & Tombol Bulat Progres:** Mengubah warna pengisi garis geser dan tombol bulat aktif (**25**, **50**, **75**) sesuai warna tema, sambil mempertahankan warna **hijau cerah pada angka 100** sebagai sinyal psikologis bahwa tugas tuntas dikerjakan.
4. **Warna Label Prioritas & Kategori:** Mengatur ulang warna tiga label prioritas (misalnya menjadikan prioritas rendah berwarna hijau muda atau biru lembut) serta warna label kategori agar tetap mudah dibaca dalam sekilas pandang.
5. **Warna Banner Pesan Sukses:** Mengubah warna kotak pemberitahuan keberhasilan di bagian atas halaman agar tetap nyaman di mata dan kontras dengan kotak peringatan merah muda.

---

## 4. Naskah Narasi Demo 2 Menit (Siap Ucap di Depan Penguji)

> *"Selamat pagi Bapak/Ibu penguji. Hari ini saya akan mendemokan aplikasi **TaskPriority**, yaitu aplikasi To-Do List berbobot yang dirancang agar pengguna mudah menyusun tugas harian berdasarkan kategori dan tingkat prioritasnya.*
>
> *[Buka layar Halaman Masuk]*  
> *Di layar awal ini pengguna disambut halaman Masuk. Jika belum punya akun, pengguna bisa mengklik tautan **Daftar Akun Baru** di bawah kartu ini untuk membuat akun pribadi, karena setiap akun memiliki ruang kerja masing-masing yang terpisah sehingga data tugasnya tidak tercampur dengan pengguna lain. Di sini saya coba masuk memakai akun yang sudah terdaftar.*
>
> *[Ketik email & kata sandi, lalu klik tombol Masuk Sekarang]*  
> *Begitu masuk, muncul banner hijau selamat datang dan kita langsung melihat halaman utama **Manajemen Tugas**. Di bagian kanan atas ada nama pengguna beserta tombol **Keluar**, lalu di bawahnya ada ringkasan cepat jumlah tugas **Total**, **Selesai**, dan **Tertunda**.*
>
> *[Arahkan kursor ke kartu Tambah Tugas Baru di bagian atas]*  
> *Sekarang saya contohkan cara menambah tugas baru. Saat saya klik kotak **Judul Tugas**, garis tepinya langsung menyala biru keunguan agar pengguna fokus melihat kolom yang sedang diketik. Misalnya saya isi judul 'Menyiapkan Presentasi Proyek', lalu saya pilih kategorinya **Sekolah**, tingkat prioritasnya saya pilih **High** karena mendesak, dan tanggal tenggatnya saya atur besok. Setelah itu saya klik tombol **Simpan Tugas**.*
>
> *[Klik Simpan Tugas, tunjuk banner hijau dan kartu baru di daftar]*  
> *Muncul pesan sukses berwarna hijau di bagian atas, dan tugasnya langsung tampil di daftar paling atas. Bapak/Ibu bisa melihat label warnanya langsung membedakan tugas ini: kategori **Sekolah** berwarna biru keunguan, sedangkan prioritas **High** otomatis berwarna merah menyala dengan ikon api supaya langsung terlihat mana pekerjaan yang paling mendesak.*
>
> *[Arahkan kursor ke bagian bawah kartu tugas pada kontrol Tingkat Progres]*  
> *Untuk mencatat kemajuan pengerjaan, pengguna tidak hanya punya kotak centang selesai, tetapi juga garis geser progres dan tombol bulat pilihan cepat **25**, **50**, **75**, dan **100**. Jika baru dikerjakan separuh, tinggal klik tombol **50**, persentasenya langsung naik jadi **50%**. Jika tugas sudah beres, pengguna bisa menggeser garisnya mentok ke kanan atau klik tombol bulat **100**, maupun langsung mencentang kotak di kiri atas. Seketika judulnya tercoret rapi, statusnya berubah hijau **Selesai**, dan hitungan tugas selesai di atas langsung bertambah.*
>
> *[Arahkan kursor ke bilah Filter, lalu ke tombol Edit dan Hapus]*  
> *Jika daftar tugas sudah sangat banyak, di tengah layar ada bilah pencarian dan filter berdasarkan status maupun kategori. Misalnya saya pilih status **Belum Selesai** atau kategori **Sekolah** lalu klik **Filter**, daftarnya langsung tersaring. Untuk mengembalikan semua daftar, cukup klik tombol silang. Kalau ada catatan yang salah ketik, kita klik ikon pensil untuk membukanya di jendela pop-up dan menyimpannya kembali. Dan kalau ingin menghapus tugas, saat saya klik ikon tempat sampah, aplikasi tidak langsung menghapus melainkan memunculkan kotak dialog konfirmasi dulu agar tidak terjadi kesalahan klik.*
>
> *[Arahkan kursor ke pojok kanan atas lalu klik tombol Keluar]*  
> *Terakhir, setelah selesai memakai aplikasi, saya klik tombol **Keluar**. Sama seperti tombol hapus, akan muncul konfirmasi terlebih dahulu sebelum ruang kerja dikunci kembali ke halaman Masuk.*
>
> *Sekian demonstrasi tampilan dan alur penggunaan aplikasi To-Do List berbobot ini. Terima kasih."*
