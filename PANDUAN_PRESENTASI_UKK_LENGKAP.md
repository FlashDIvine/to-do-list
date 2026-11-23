# Panduan Presentasi & Ujian Lisan UKK — Web To-Do List Berbobot

Dokumen ini merupakan panduan lengkap persiapan presentasi dan tanya jawab di hadapan penguji untuk proyek aplikasi **TaskPriority (Web To-Do List Berbobot)**. Seluruh penjelasan ditulis menggunakan bahasa sehari-hari dan analogi dunia nyata agar mudah dipahami dan diucapkan dengan percaya diri saat ujian praktik.

---

## 1. Sistem & Teknologi yang Digunakan (Arsitektur Aplikasi)

### Mesin Utama Aplikasi (Framework Laravel)
Aplikasi ini dibangun di atas mesin utama **Laravel** dengan pola kerja **Model-View-Controller** (atau disingkat MVC). Agar mudah dibayangkan, cara kerjanya persis seperti sebuah **restoran yang terorganisir rapi**:
* **Controller berperan sebagai Pelayan:** Ia menyambut tamu yang datang, memeriksa apakah pesanannya valid, lalu berkoordinasi antara bagian dapur dan meja tamu.
* **Model berperan sebagai Kepala Dapur:** Ia satu-satunya pihak yang berhak membuka lemari bahan makanan (penyimpanan data) dan memastikan aturan pencatatan dipatuhi.
* **View berperan sebagai Piring Saji:** Ia menata hasil masakan menjadi hidangan visual yang rapi dan sedap dipandang di layar pengguna.

**Mengapa memilih Laravel?**  
Ibarat membangun rumah, kita tidak perlu mencetak batu bata atau merakit instalasi listrik dari nol. Framework Laravel sudah menyediakan kerangka pondasi yang kokoh, mempercepat proses pembuatan fitur, memisahkan tugas tiap bagian dengan jelas agar mudah dirawat, serta sudah menanamkan pengaman standar industri secara bawaan.

### Tampilan Antarmuka (Blade & Tailwind CSS)
* **Blade (Mesin Tata Letak Halaman):** Berfungsi menyatukan kerangka umum aplikasi (seperti bilah menu atas, area pesan notifikasi, dan bagian bawah halaman) sehingga setiap halaman selalu tampil seragam tanpa perlu menggambar ulang bagian atasnya berkali-kali.
* **Tailwind CSS (Penata Gaya Visual):** Digunakan untuk mengatur warna, jarak, bayangan kartu, hingga ukuran tombol secara langsung. Tampilan dimuat secara instan tanpa proses perakitan berat, ringan dibuka, dan **responsif**—artinya susunan formulir serta kartu tugas otomatis menyesuaikan diri agar tetap nyaman dilihat baik di layar laptop yang lebar maupun di layar ponsel yang sempit.

### Sistem Keamanan & Hak Akses (3 Lapis Perlindungan)
Aplikasi ini menjaga data pengguna dengan tiga lapis pengaman berlapis:
1. **Sistem Kunci Ruangan (Autentikasi Login & Sesi):**  
   Bekerja seperti resepsionis dan kunci kartu kamar hotel. Pengunjung luar (tamu) hanya diizinkan melihat halaman Masuk dan Pendaftaran. Jika tamu mencoba membuka halaman daftar tugas secara paksa, gerbang otomatis mengarahkannya ke halaman Masuk. Sebaliknya, pengguna yang sudah punya tiket sesi aktif akan langsung diarahkan masuk ke ruang kerjanya tanpa diminta mendaftar ulang. Kata sandi pengguna juga digiling satu arah menjadi kode acak agar tidak terbaca oleh siapa pun, dan setiap kali berhasil masuk, tiket sesi selalu diperbarui supaya tiket lama tidak dapat disalahgunakan.
2. **Sistem Pemisah Loker Pribadi (Anti-IDOR):**  
   Setiap akun pengguna ibarat memiliki loker penyimpanan pribadi yang terkunci rapat. Saat pengguna melihat daftar, mencentang status selesai, menggeser progres, mengubah, atau menghapus tugas, aplikasi selalu memeriksa terlebih dahulu apakah nomor tugas tersebut benar-benar milik akun yang sedang aktif. Jika ada pengguna nakal yang mencoba menebak nomor tugas milik orang lain melalui bilah alamat browser untuk mengintip atau menghapusnya, gerbang pengaman langsung memblokir akses dan menampilkan pesan bahwa data tidak ditemukan.
3. **Sistem Tiket Validasi Formulir (Proteksi CSRF):**  
   Setiap formulir yang mengubah data (mulai dari masuk akun, mendaftar, menyimpan tugas, mengubah isi, menggeser progres, menghapus tugas, hingga keluar akun) wajib membawa stempel tiket rahasia resmi yang dicetak khusus untuk sesi pengguna tersebut. Ibarat surat izin berstempel basah: jika ada pihak luar yang mencoba mengirimkan perintah palsu secara diam-diam dari situs lain atas nama pengguna, aplikasi langsung menolak pengiriman tersebut karena stempelnya tidak cocok.

---

## 2. Database yang Digunakan & Rancangan Datanya

### Jenis Database (SQLite)
Aplikasi ini menggunakan **SQLite**, yaitu sistem penyimpanan data berbasis **satu berkas tunggal** yang tersimpan langsung di dalam folder proyek.

* **Analogi Dunia Nyata:** Jika sistem basis data konvensional seperti menyewa gedung gudang besar yang butuh petugas penjaga dan mesin pendingin terpisah agar bisa beroperasi, **SQLite** ibarat **satu buku arsip induk yang ringkas dan selalu siap dibawa di dalam tas kerja**.
* **Alasan Pemilihan untuk Demo UKK:** SQLite sangat ringan, cepat, mandiri, dan **tidak memerlukan instalasi maupun penyalaan aplikasi server basis data tambahan**. Hal ini membuat aplikasi 100% stabil, portabel, dan siap didemokan secara offline di komputer penguji tanpa kendala koneksi.

### Isi Lemari Data (Penjelasan Setiap Tabel)
Secara fungsional, data utama aplikasi disimpan di dalam tiga lemari tabel inti:

1. **Tabel Pengguna:** Berfungsi menyimpan identitas pemilik ruang kerja pribadi. Di dalamnya tercatat:
   * Nomor identitas unik pengguna.
   * Nama lengkap pengguna.
   * Alamat email (bersifat unik, satu email hanya untuk satu akun).
   * Kata sandi (tersimpan dalam bentuk acakan terenkripsi satu arah, bukan tulisan asli).
   * Penanda ingat saya serta catatan waktu kapan akun dibuat dan diperbarui.

2. **Tabel Kategori:** Berfungsi menyimpan daftar kelompok tugas sebagai data acuan tetap. Di dalamnya tercatat:
   * Nomor identitas unik kategori.
   * Nama kategori (otomatis terisi tiga kategori bawaan saat persiapan awal, yaitu **Sekolah**, **Pribadi**, dan **Pekerjaan**).
   * Catatan waktu pembuatan dan pembaruan kategori.

3. **Tabel Tugas:** Berfungsi menyimpan seluruh catatan pekerjaan yang dibuat pengguna. Di dalamnya tercatat:
   * Nomor identitas unik tugas (berupa angka urut otomatis).
   * Penanda pemilik tugas (merujuk ke satu pengguna di Tabel Pengguna).
   * Penanda kategori tugas (merujuk ke satu kategori di Tabel Kategori).
   * **Kolom Judul:** Nama ringkas tugas (wajib diisi, maksimal 255 huruf).
   * **Kolom Deskripsi:** Catatan tambahan mengenai rincian tugas (bersifat opsional atau boleh dikosongkan).
   * **Kolom Prioritas:** Bobot tingkat kepentingan tugas yang dibatasi hanya pada tiga pilihan yaitu *Rendah*, *Sedang* (pilihan bawaan), dan *Tinggi*.
   * **Kolom Tenggat Waktu:** Tanggal batas penyelesaian tugas (bersifat opsional atau boleh dikosongkan).
   * **Kolom Persentase Progres:** Angka kemajuan pengerjaan dari 0 hingga 100 (bawaannya bernilai 0).
   * **Kolom Status Selesai:** Penanda apakah tugas sudah selesai atau belum (bawaannya belum selesai, dan otomatis berubah menjadi selesai apabila persentase progres menyentuh angka 100).
   * Catatan waktu kapan tugas dibuat dan terakhir kali diubah.

*(Catatan tambahan: di dalam berkas penyimpanan juga terdapat beberapa tabel pendukung bawaan mesin aplikasi yang bertugas mencatat riwayat struktur tabel, daftar sesi pengguna, penyimpanan sementara, serta antrean latar belakang agar aplikasi berjalan mulus.)*

### Hubungan Antardata (Relasi Tabel)
Ketiga tabel utama tersebut saling terikat oleh aturan hubungan yang jelas:
* **Satu Pengguna Memiliki Banyak Tugas (One-to-Many):**  
  *Analogi:* Seperti **satu siswa yang memiliki satu tas sekolah berisi banyak buku catatan**. Satu akun pengguna dapat membuat puluhan bahkan ratusan tugas, tetapi setiap satu catatan tugas hanya dimiliki secara sah oleh satu akun pengguna saja.
* **Satu Kategori Menaungi Banyak Tugas (One-to-Many):**  
  *Analogi:* Seperti **satu laci berlabel "Sekolah" yang bisa menampung banyak map tugas sekaligus**, namun setiap map tugas hanya dimasukkan ke dalam satu laci kategori tertentu.
* **Apa yang Terjadi Jika Akun Pengguna Dihapus?**  
  Hubungan antara pengguna dan tugas menerapkan aturan **penghapusan berantai (efek domino)**. Jika satu akun pengguna dihapus dari lemari data, seluruh tugas miliknya otomatis ikut terhapus bersih. Tujuannya agar tidak ada catatan tugas yang tertinggal mengambang tanpa pemilik. Aturan berantai serupa juga berlaku apabila suatu kategori dihapus.

---

## 3. Tur Tampilan Layar & Interaksi Warna

### Tata Letak Layar dari Awal hingga Utama
* **Halaman Masuk & Halaman Daftar:**  
  Di bagian paling atas terdapat bilah putih bersih berisi ikon centang biru keunguan dengan nama **TaskPriority**, subjudul **To-Do List Berbobot UKK RPL**, serta tautan **Masuk** dan tombol **Daftar** di pojok kanan atas. Di tengah layar terdapat kartu formulir putih berbingkai halus. Halaman Masuk berisi kotak **Alamat Email**, **Kata Sandi**, pilihan **Ingat saya**, tombol **Masuk Sekarang**, dan tautan **Daftar Akun Baru**. Halaman Daftar berisi **Nama Lengkap**, **Alamat Email**, **Kata Sandi (Min. 6 Karakter)**, **Konfirmasi Kata Sandi**, tombol **Daftarkan Akun**, dan tautan **Masuk di Sini**.
* **Halaman Utama (Dashboard Daftar Tugas):**  
  1. **Bagian Header & Ringkasan:** Pojok kanan atas menampilkan nama lengkap dan alamat email pengguna aktif beserta tombol **Keluar**. Tepat di bawah bilah atas terdapat judul besar **Manajemen Tugas** dan tiga lencana penghitung di sebelah kanan: **Total**, **Selesai**, dan **Tertunda**.
  2. **Formulir Input Tugas Baru:** Terletak di kartu paling atas bertajuk **Tambah Tugas Baru** dengan tanda bintang merah pada kolom wajib. Baris pertama menyusun sejajar **Judul Tugas**, dropdown **Kategori**, dan dropdown **Prioritas / Bobot**. Baris kedua menyusun **Tenggat Waktu (Opsional)** dan **Deskripsi / Catatan Tambahan (Opsional)**, diakhiri tombol biru keunguan **Simpan Tugas** di kanan bawah.
  3. **Area Pencarian & Penyaringan (Filter):** Berada tepat di bawah kartu tambah tugas, berisi kotak cari kata kunci berikon kaca pembesar, dropdown filter status (*Semua Status*, *Belum Selesai*, *Selesai*), dropdown filter kategori (*Semua Kategori* beserta pilihannya), tombol gelap **Filter**, dan tombol silang kecil untuk mengatur ulang pilihan saat filter sedang dipakai.
  4. **Daftar Kartu Tugas:** Tersusun rapi dari atas ke bawah dengan tugas paling baru selalu berada di urutan teratas. Bagian atas kartu menampilkan kotak centang di sebelah kiri, judul dan deskripsi di tengah, barisan label warna (**Kategori**, **Prioritas**, dan **Tenggat Waktu**) di bawahnya, serta tombol ikon **Pensil** (membuka jendela pop-up **Perbarui Tugas**) dan **Tempat Sampah** (hapus) di sebelah kanan. Bagian bawah kartu menampilkan bilah **Tingkat Progres** lengkap dengan persentase angka, garis geser, serta empat tombol bulat pilihan cepat (**25**, **50**, **75**, dan **100**). Jika daftar masih kosong atau hasil pencarian nihil, muncul kotak berbingkai garis putus-putus dengan pesan ramah di tengah.

### Reaksi Visual Kotak Input Saat Aktif & Saat Terjadi Kesalahan
* **Saat Diklik Aktif (Fokus):** Dalam keadaan diam, kotak isian berbingkai abu-abu muda yang lembut. Begitu pengguna mengklik salah satu kotak ketik, garis tepinya seketika berubah menjadi **biru keunguan tegas disertai sorotan cahaya lembut di sekeliling kotak**. Ini memberi umpan balik langsung agar mata pengguna mudah melacak kolom mana yang sedang diisi.
* **Saat Terjadi Kesalahan Pengisian (Validasi Merah):** Jika pengguna mencoba menekan tombol simpan saat kolom wajib belum diisi, peramban langsung menahan proses dan menandai kolom yang kosong dengan balon peringatan. Apabila ada isian yang gagal saat dikirim (misalnya kata sandi pendaftaran di bawah 6 huruf, konfirmasi sandi tidak cocok, atau kombinasi masuk keliru), layar memunculkan **kotak banner peringatan berwarna merah muda tegas berikon tanda seru** di bagian atas halaman yang merinci letak kesalahannya.

### Arti Kode Warna & Peta Modifikasi Cepat
* **Kode Warna Label Prioritas & Tenggat Waktu:**
  * **Merah berikon api (High Priority):** Menandakan tugas mendesak berbobot tinggi agar langsung memancing fokus pengguna.
  * **Kuning keemasan berikon petir (Medium Priority):** Menandakan tugas berprioritas sedang.
  * **Abu-abu sejuk berikon tunas hijau (Low Priority):** Menandakan tugas santai atau berbobot rendah.
  * **Label Tenggat Waktu Merah:** Jika suatu tugas belum selesai namun tanggal tenggatnya sudah lewat, label tanggal otomatis berubah merah disertai tulisan **Terlewat**.
* **Kode Warna Label Kategori:** Memakai latar **biru keunguan lembut berikon folder** supaya tampak jelas terpisah dari label prioritas.
* **Kontras Warna Tombol Aksi:**
  * Tombol produktif utama (**Simpan Tugas**, **Simpan Perubahan**, **Masuk Sekarang**, **Daftarkan Akun**) berwarna solid **biru keunguan cerah** agar menonjol sebagai tombol utama.
  * Tombol **Filter** berwarna **abu-abu gelap** agar tetap rapi tanpa mencuri perhatian dari tombol simpan.
  * Tombol aksi sensitif (**Hapus** dan **Keluar**) berwarna putih bersih saat diam agar tampilan terasa tenang, lalu langsung menyala **merah** ketika didekati kursor dan meminta persetujuan kotak dialog konfirmasi saat diklik.
* **Peta Bagian Warna yang Mudah Dimodifikasi Jika Diminta Penguji:**  
  Apabila penguji meminta perubahan nuansa warna (misalnya ke hijau segar, biru laut, atau oranye hangat), area yang paling mudah disesuaikan adalah:
  1. Warna ikon logo pojok kiri atas beserta seluruh tombol aksi utama (**Simpan**, **Masuk**, **Daftar**).
  2. Warna garis tepi nyala dan sorotan cahaya pada kotak isian saat diklik agar serasi dengan tema baru.
  3. Warna garis pengisi geseran progres dan tombol bulat persentase (**25**, **50**, **75**), sambil mempertahankan hijau cerah pada angka **100** sebagai penanda tugas tuntas.
  4. Nuansa warna pada tiga label prioritas dan label kategori agar kontrasnya sesuai selera penguji.
  5. Warna kotak banner notifikasi keberhasilan di bagian atas layar.

---

## 4. Alur Penggunaan Aplikasi (Workflow dari Awal sampai Selesai)

1. **Mendaftar atau Masuk Akun:** Pengguna baru membuat akun di halaman Pendaftaran atau langsung masuk memakai email dan kata sandi di halaman Masuk. Setelah berhasil, banner hijau selamat datang muncul dan pengguna masuk ke ruang kerja pribadinya yang terisolasi dari akun orang lain.
2. **Mencatat Tugas Baru:** Pada kartu paling atas, pengguna mengisi judul tugas, memilih kategori, menentukan bobot prioritas, memilih tanggal tenggat bila ada, lalu menulis deskripsi opsional dan menekan tombol **Simpan Tugas**.
3. **Melihat Notifikasi Berhasil:** Seketika muncul banner hijau di atas halaman yang mengonfirmasi bahwa tugas berhasil ditambahkan, angka statistik Total dan Tertunda bertambah, dan kartu tugas baru langsung muncul paling atas.
4. **Mencentang Tugas Selesai & Mengatur Progres:** Pengguna dapat mengklik kotak centang di sisi kiri kartu untuk menandai tugas selesai seketika (**100%**) sehingga judul serta catatan otomatis tercoret garis tengah dan memudar abu-abu (diklik ulang akan mengembalikan status ke **0%**). Pengguna juga dapat menggeser garis progres atau menekan tombol bulat **25**, **50**, **75**, atau **100** di bawah kartu; jika menyentuh **100**, tugas otomatis berstatus **Selesai** dan angka penghitung di bagian atas halaman langsung ikut terbarui tanpa kedipan layar.
5. **Menyaring & Mencari Tugas:** Pengguna mengetik kata kunci di kotak pencarian, memilih status atau kategori tertentu, lalu menekan **Filter** untuk mempersempit tampilan daftar. Tombol silang di sebelahnya ditekan untuk membersihkan filter dan menampilkan seluruh tugas kembali.
6. **Mengedit atau Menghapus Tugas:** Pengguna menekan ikon **Pensil** untuk membuka jendela pop-up **Perbarui Tugas**, mengubah rincian atau progresnya, lalu menekan **Simpan Perubahan**. Untuk menghapus, pengguna menekan ikon **Tempat Sampah**, menyetujui kotak dialog konfirmasi agar tidak salah hapus, lalu melihat banner hijau konfirmasi penghapusan.
7. **Keluar Akun:** Setelah selesai, pengguna menekan tombol **Keluar** di pojok kanan atas, menyetujui kotak dialog konfirmasi keluar, dan layar langsung mengunci ruang kerja kembali ke halaman Masuk beserta pesan berhasil keluar.

---

## 5. Naskah Presentasi Demo 3 Menit (Siap Ucap ke Penguji)

> *"Selamat pagi Bapak dan Ibu penguji. Perkenalkan, saya siap mempresentasikan proyek UKK saya berupa aplikasi **TaskPriority**, yaitu aplikasi manajemen To-Do List berbobot yang dirancang agar aman, rapi, dan mudah dipakai.*
>
> *Secara arsitektur, aplikasi ini dibangun menggunakan mesin utama **Framework Laravel** dengan pola **Model-View-Controller** untuk memisahkan pengatur lalu lintas data, pengolahan aturan, dan tampilan layar. Untuk antarmukanya, saya memakai **Blade** dan **Tailwind CSS** sehingga tampilannya bersih dan otomatis menyesuaikan ukuran layar laptop maupun ponsel. Dari sisi penyimpanan data, saya memakai **SQLite**, yaitu basis data berbasis satu berkas tunggal yang sangat ringan dan portabel sehingga langsung berjalan stabil saat demo offline tanpa perlu menyalakan server basis data tambahan. Di dalamnya terdapat tiga tabel utama yang saling terhubung: tabel Pengguna, tabel Kategori, dan tabel Tugas, di mana satu pengguna maupun satu kategori bisa menaungi banyak tugas, dengan penghapusan berantai sehingga data tugas otomatis bersih bila akun induknya dihapus.*
>
> *Untuk menjaga keamanan data, aplikasi ini sudah menerapkan tiga lapis perlindungan: pertama gerbang sesi login yang memisahkan tamu dan pengguna terdaftar, kedua pengunci ruang kerja pribadi atau Anti-IDOR sehingga pengguna satu tidak bisa mengintip maupun mengubah tugas milik pengguna lain lewat alamat web, dan ketiga validasi tiket rahasia pada setiap formulir untuk mencegah pengiriman data palsu dari luar aplikasi.*
>
> *[Tunjukkan Halaman Masuk di layar]*  
> *Sekarang saya masuk ke demo aplikasinya. Di layar awal ini terdapat Halaman Masuk, dan jika belum punya akun tersedia tautan ke Halaman Pendaftaran. Saya coba masuk memakai akun yang sudah tersedia.*
>
> *[Ketik email dan kata sandi, lalu klik Masuk Sekarang]*  
> *Begitu masuk, muncul banner hijau selamat datang dan kita langsung melihat halaman utama **Manajemen Tugas**. Di kanan atas tertera identitas akun aktif dan tombol **Keluar**, lalu di bawahnya terdapat ringkasan angka **Total**, **Selesai**, dan **Tertunda**.*
>
> *[Klik kotak Judul Tugas pada kartu Tambah Tugas Baru]*  
> *Saat saya mengklik kotak isian, garis tepinya menyala biru keunguan sebagai penunjuk fokus. Saya coba tambah tugas baru berjudul 'Menyiapkan Laporan Uji Kompetensi', memilih kategori **Sekolah**, memberi prioritas **High**, dan mengatur tenggat waktunya, lalu saya klik **Simpan Tugas**.*
>
> *[Klik Simpan Tugas]*  
> *Banner pesan berhasil hijau langsung tampil di atas, dan kartu tugas baru muncul paling atas. Pengkodean warnanya langsung membedakan tiap informasi: kategori **Sekolah** berwarna biru keunguan lembut, sedangkan prioritas **High** berwarna merah menyala dengan ikon api agar tugas paling mendesak langsung terlihat.*
>
> *[Arahkan kursor ke bagian bawah kartu pada kontrol Tingkat Progres]*  
> *Di bagian bawah kartu ini, pengguna dapat melacak progres pengerjaan lewat garis geser maupun tombol bulat cepat **25**, **50**, **75**, dan **100**. Jika diklik **50**, progres langsung tercatat lima puluh persen. Saat digeser mentok ke kanan atau tombol **100** diklik—atau lewat kotak centang di kiri atas—tugas otomatis ditandai **Selesai**, judulnya tercoret, warnanya berubah hijau, dan penghitung **Selesai** di atas langsung bertambah.*
>
> *[Arahkan kursor ke bilah Filter, lalu ikon Pensil, dan ikon Tempat Sampah]*  
> *Untuk mencari tugas, pengguna tinggal memakai kotak pencarian serta pilihan filter status dan kategori lalu menekan **Filter**, dan tombol silang untuk meresetnya. Untuk mengubah data, ikon **Pensil** memunculkan jendela pop-up ubah tugas. Sedangkan untuk menghapus, ikon **Tempat Sampah** memunculkan dialog konfirmasi dulu guna mencegah salah tekan.*
>
> *[Klik tombol Keluar di pojok kanan atas]*  
> *Terakhir, ketika tombol **Keluar** ditekan, aplikasi kembali meminta konfirmasi sebelum menutup ruang kerja dan mengarahkan layar kembali ke Halaman Masuk.*
>
> *Demikian presentasi sistem, basis data, dan demonstrasi aplikasi To-Do List berbobot ini. Apabila Bapak atau Ibu penguji ada pertanyaan maupun permintaan penyesuaian, saya siap menjawab. Terima kasih."*

