# 📖 PANDUAN LENGKAP PERTANYAAN & JAWABAN UJI KOMPETENSI KEAHLIAN (UKK)
## Skema: Junior Web Programmer
### Proyek: Sistem Penggajian Karyawan & Cetak Slip Gaji (UKK Penggajian)
### Developer: Muhammad Ruslan

---

> [!TIP]
> **Petunjuk Penggunaan Dokumen Ini:**
> - Pelajari dan pahami konsepnya, jangan hanya menghafal kata per kata.
> - Jawablah dengan intonasi yang sopan, santai, percaya diri, dan tersenyum.
> - Bila asesor meminta kamu menunjukkan codingan, buka file yang sudah dicantumkan lokasinya di setiap jawaban.

---

## 📑 DAFTAR ISI
1. [Kategori 1: Gambaran Umum & Konsep Aplikasi](#kategori-1-gambaran-umum--konsep-aplikasi)
2. [Kategori 2: Arsitektur MVC & Framework Laravel](#kategori-2-arsitektur-mvc--framework-laravel)
3. [Kategori 3: Database, Migrasi, dan Model](#kategori-3-database-migrasi-dan-model)
4. [Kategori 4: Alur Autentikasi & Keamanan Sistem](#kategori-4-alur-autentikasi--keamanan-sistem)
5. [Kategori 5: Logika Bisnis & Perhitungan Gaji](#kategori-5-logika-bisnis--perhitungan-gaji)
6. [Kategori 6: Fitur Khusus (Captcha, WhatsApp, Gmail, Terbilang)](#kategori-6-fitur-khusus)
7. [Kategori 7: Tampilan Antarmuka, CSS, & Fitur Cetak](#kategori-7-tampilan-antarmuka-css--fitur-cetak)
8. [Kategori 8: Pengujian Sistem (Testing & PHPUnit)](#kategori-8-pengujian-sistem)
9. [Kategori 9: Pertanyaan Kritis / Jebakan Asesor](#kategori-9-pertanyaan-kritis--jebakan-asesor)
10. [Kategori 10: Langkah-langkah Demo Aplikasi di Depan Asesor](#kategori-10-langkah-demo-aplikasi)

---

## KATEGORI 1: GAMBARAN UMUM & KONSEP APLIKASI

### Q1: Aplikasi apa yang kamu buat dan apa tujuan utamanya?
**Jawaban Singkat & Padat:**
> "Aplikasi yang saya buat adalah **Sistem Penggajian Karyawan dan Cetak Slip Gaji**. Tujuan utamanya untuk mendigitalisasi dan mengotomatisasi pencatatan gaji karyawan, menghitung gaji bersih secara akurat sesuai ketentuan soal UKK, serta memudahkan pencetakan slip gaji resmi dan pengiriman slip ke karyawan via WhatsApp dan Gmail."

### Q2: Siapa target pengguna dari aplikasi ini?
**Jawaban:**
> "Target pengguna utamanya adalah bagian **Admin / HRD / Keuangan (Bendahara)** yang bertugas mengelola data penggajian karyawan dan mencetak slip gaji setiap periode."

### Q3: Fitur apa saja yang terdapat di dalam aplikasi ini?
**Jawaban:**
> "Fitur utamanya meliputi:
> 1. **Autentikasi Pengguna:** Login dengan proteksi password terenkripsi bcrypt dan fitur toggle lihat kata sandi.
> 2. **Dashboard / Riwayat Slip Gaji:** Tabel arsip slip gaji lengkap dengan pencarian realtime serta 3 kartu ringkasan (total slip, total uang keluar, dan rata-rata gaji).
> 3. **Form Tambah Slip Gaji:** Input periode gaji dengan kalender (dari tanggal - sampai tanggal), kalkulasi gaji bersih otomatis secara realtime di layar, dan verifikasi keamanan Captcha perkalian.
> 4. **Halaman Detail Slip Gaji:** Menampilkan rincian slip, konversi angka ke teks terbilang otomatis, tanda tangan penerima, dan tombol integrasi kirim WhatsApp & Gmail.
> 5. **Cetak Slip Gaji & Simpan PDF:** Format cetak dokumen bersih (print-ready) yang otomatis memicu dialog cetak browser."

---

## KATEGORI 2: ARSITEKTUR MVC & FRAMEWORK LARAVEL

### Q4: Mengapa kamu memilih framework Laravel untuk proyek ini?
**Jawaban:**
> "Saya menggunakan Laravel karena:
> 1. Memiliki struktur **MVC** yang rapi dan terstandar industri.
> 2. Sudah dilengkapi fitur keamanan bawaan seperti proteksi CSRF, SQL Injection prevention via PDO, dan hashing password.
> 3. Mempermudah manipulasi database menggunakan **Eloquent ORM** dan **Migration**.
> 4. Templating engine **Blade** yang fleksibel dan mudah dipelihara."

### Q5: Apa itu MVC dan bagaimana penerapannya di proyek ini?
**Jawaban:**
> "MVC adalah pola arsitektur perangkat lunak yang memisahkan aplikasi menjadi tiga komponen:
> - **Model:** Mengelola data dan aturan bisnis dengan database. Di proyek ini contohnya file `app/Models/SlipGaji.php` dan `app/Models/User.php`.
> - **View:** Mengatur tampilan antarmuka ke pengguna (HTML/CSS). Di proyek ini berada di folder `resources/views/` (misalnya `index.blade.php`, `create.blade.php`, `cetak.blade.php`).
> - **Controller:** Sebagai penghubung atau otak yang menerima request dari user, memanggil Model, memproses logika, dan mengirim data ke View. Di proyek ini adalah `app/Http/Controllers/SlipGajiController.php` dan `AuthController.php`."

### Q6: Jelaskan alur kerja (request lifecycle) dari saat pengguna mengetik URL hingga halaman muncul!
**Jawaban:**
> 1. Pengguna membuka browser dan mengakses URL, misalnya `/slip-gaji`.
> 2. Request diterima oleh file routing di `routes/web.php`.
> 3. Request disaring oleh **Middleware** `cek.login` untuk memastikan user sudah login.
> 4. Jika lolos, route memanggil method `index()` di `SlipGajiController.php`.
> 5. Controller mengambil data dari database melalui Model `SlipGaji::all()`.
> 6. Controller mengirimkan data tersebut ke view `resources/views/gaji/index.blade.php`.
> 7. Blade me-render kode menjadi HTML dan menampilkannya di layar browser pengguna.

### Q7: Di mana file routing berada dan bagaimana cara mendaftarkannya?
**File Lokasi:** `routes/web.php`
**Jawaban:**
> "File routing berada di `routes/web.php`. Contoh cara mendaftarkannya:
> ```php
> Route::get('/slip-gaji', [SlipGajiController::class, 'index'])->name('slip-gaji.index');
> ```
> Kami juga mengelompokkan route yang butuh login menggunakan `Route::middleware(['cek.login'])->group(...)`."

---

## KATEGORI 3: DATABASE, MIGRASI, DAN MODEL

### Q8: Database apa yang kamu gunakan dan apa saja tabelnya?
**Jawaban:**
> "Saya menggunakan database **MySQL / MariaDB** dengan nama database `ukk_penggajian`. Terdapat 2 tabel utama:
> 1. Tabel `users`: untuk menyimpan akun login admin.
> 2. Tabel `slip_gaji`: untuk menyimpan seluruh arsip data penggajian karyawan."

### Q9: Mengapa menggunakan tipe data DECIMAL untuk nominal gaji, bukan INTEGER?
**Jawaban Sangat Bagus (Nilai Plus):**
> "Karena nominal mata uang membutuhkan presisi yang akurat dan berpotensi memiliki angka yang sangat besar di atas batas Integer standar (2 milyar). Tipe data `DECIMAL(15, 2)` menjamin tidak terjadi kehilangan nilai atau pembulatan tidak akurat, serta mampu menampung nominal hingga triliunan rupiah."

### Q10: Apa itu Migration di Laravel dan apa keuntungannya?
**File Lokasi:** `database/migrations/`
**Jawaban:**
> "Migration adalah fitur *version control* untuk database di Laravel. Keuntungannya:
> 1. Kita tidak perlu membuat tabel secara manual di phpMyAdmin.
> 2. Struktur tabel dapat dibuat seragam di semua komputer tim atau penguji hanya dengan perintah `php artisan migrate`.
> 3. Riwayat perubahan skema database tercatat dengan rapi."

### Q11: Apa itu Seeder dan apa gunanya?
**File Lokasi:** `database/seeders/DatabaseSeeder.php`
**Jawaban:**
> "Seeder adalah fitur Laravel untuk mengisi data awal atau data uji coba (dummy) ke database secara otomatis. Di proyek ini, `DatabaseSeeder.php` digunakan untuk membuat akun admin default (`admin` / `admin123`) dan 3 data sampel slip gaji."

### Q12: Apa fungsi `$fillable` pada file Model?
**File Lokasi:** `app/Models/SlipGaji.php` & `app/Models/User.php`
**Jawaban:**
> "`$fillable` adalah proteksi keamanan Laravel (Mass Assignment Protection) yang menentukan kolom-kolom mana saja yang diizinkan untuk diisi secara langsung melalui fungsi `create()` atau `update()`."

---

## KATEGORI 4: ALUR AUTENTIKASI & KEAMANAN SISTEM

### Q13: Bagaimana alur proses login yang kamu buat?
**File Lokasi:** `app/Http/Controllers/AuthController.php`
**Jawaban:**
> 1. Pengguna memasukkan username dan password di form `resources/views/auth/login.blade.php`.
> 2. Controller memvalidasi input tidak boleh kosong.
> 3. Controller mencari user di database berdasarkan `username`.
> 4. Jika user ditemukan, password yang diketik dicocokkan dengan password terenkripsi di database menggunakan `Hash::check()`.
> 5. Jika cocok, sistem mendaftarkan sesi login pengguna menggunakan `Auth::login($user)` dan mengarahkan ke halaman riwayat slip gaji.
> 6. Jika gagal, sistem mengembalikan user ke login dengan pesan error flash session.

### Q14: Mengapa password tidak boleh disimpan dalam bentuk teks biasa (plain text)?
**Jawaban:**
> "Karena jika database berhasil diakses atau bocor oleh pihak tidak berwenang, password pengguna tetap aman dan tidak bisa dibaca. Di Laravel kami menggunakan algoritma **Bcrypt** melalui fungsi `Hash::make()` yang menghasilkan enkripsi satu arah (*one-way hashing*)."

### Q15: Apa fungsi Middleware `CekLogin`?
**File Lokasi:** `app/Http/Middleware/CekLogin.php`
**Jawaban:**
> "Middleware bertindak sebagai satpam atau filter di depan route. Middleware `CekLogin` memeriksa apakah pengguna sudah login (`Auth::check()`). Jika belum login dan mencoba mengakses URL seperti `/slip-gaji`, middleware langsung menolak dan mengarahkan kembali ke halaman login dengan pesan peringatan."

### Q16: Apa itu `@csrf` yang ada di setiap form Blade?
**Jawaban:**
> "`@csrf` menghasilkan token rahasia (Cross-Site Request Forgery token). Fungsinya melindungi aplikasi dari serangan pihak ketiga jahat yang mencoba mengirimkan request form palsu atas nama user yang sedang aktif."

---

## KATEGORI 5: LOGIKA BISNIS & PERHITUNGAN GAJI

### Q17: Bagaimana rumus perhitungan gaji bersih pada soal UKK ini?
**File Lokasi:** `app/Http/Controllers/SlipGajiController.php`
**Jawaban:**
> "Sesuai ketentuan soal UKK:
> 1. **Total Penghasilan (A)** = Gaji Pokok + Uang Lembur
> 2. **Total Potongan (B)** = Pinjaman Karyawan
> 3. **Gaji Bersih Diterima** = Total Penghasilan (A) - Total Potongan (B)"

### Q18: Mengapa perhitungan gaji ada di JavaScript dan juga di Controller PHP?
**Jawaban Keren (Sangat Disukai Asesor):**
> "Perhitungan di **JavaScript (Frontend)** berfungsi untuk kenyamanan pengguna (*User Experience*), agar user langsung melihat angka kalkulasi secara otomatis saat mengetik tanpa perlu reload halaman.
> Sedangkan perhitungan di **Controller PHP (Backend)** berfungsi untuk keamanan dan integritas data (*Data Integrity*), karena data di frontend bisa saja dimanipulasi atau di-inspect element oleh pengguna."

### Q19: Bagaimana nomor slip gaji (`no_slip`) dibuat secara otomatis?
**File Lokasi:** `app/Http/Controllers/SlipGajiController.php` (baris ~65)
**Jawaban:**
> "Nomor slip dibuat otomatis dengan format: `SLIP-TAHUNBULAN-NOMORURUT`.
> Contohnya: `SLIP-202609-001`. Sistem menghitung jumlah data slip yang ada ditambah 1, lalu diformat 3 digit angka menggunakan fungsi `str_pad()`."

---

## KATEGORI 6: FITUR KHUSUS (CAPTCHA, WHATSAPP, GMAIL, TERBILANG)

### Q20: Bagaimana cara kerja fitur Captcha perkalian yang kamu buat?
**File Lokasi:** `app/Http/Controllers/SlipGajiController.php` & `resources/views/gaji/create.blade.php`
**Jawaban:**
> 1. Saat halaman form tambah dibuka, controller membuat 2 angka acak (misal $3 \times 4$) dan menyimpan hasil kalinya (12) ke dalam **Session** server (`session(['captcha_jawaban' => 12])`).
> 2. Pengguna mengisi hasil perkalian di form.
> 3. Saat form dikirim, controller mencocokkan input user dengan angka yang tersimpan di session. Jika salah, penyimpanan dibatalkan dan user diminta menghitung ulang."

### Q21: Bagaimana cara kerja fungsi Terbilang Rupiah?
**File Lokasi:** `app/Models/SlipGaji.php` (method `getTerbilangAttribute()`)
**Jawaban:**
> "Fungsi terbilang menggunakan algoritma rekursif sederhana. Nilai gaji bersih (misalnya 5.500.000) dipecah per kelipatan satuan, belasan, puluhan, ratusan, ribuan, jutaan, hingga milyaran, lalu dikonversi menjadi kata-kata bahasa Indonesia, misalnya: *'Lima Juta Lima Ratus Ribu Rupiah'*."

### Q22: Bagaimana cara kerja tombol kirim WhatsApp dan Gmail?
**File Lokasi:** `app/Models/SlipGaji.php` (accessor `getWaUrlAttribute` dan `getEmailUrlAttribute`)
**Jawaban:**
> - **WhatsApp:** Menggunakan WhatsApp Click to Chat API (`https://wa.me/62...`). Nomor telepon karyawan yang diawali '0' otomatis diubah ke kode negara '62', dan pesan ringkasan gaji di-encode menggunakan `urlencode()`.
> - **Gmail:** Menggunakan direct web compose link (`https://mail.google.com/mail/?view=cm&fs=1&su=...&body=...`) sehingga saat diklik langsung membuka tab Gmail baru dengan subjek dan isi rincian slip gaji yang sudah terisi rapi."

---

## KATEGORI 7: TAMPILAN ANTARMUKA, CSS, & FITUR CETAK

### Q23: Styling apa yang kamu gunakan untuk membangun tampilan web?
**Jawaban:**
> "Saya menggunakan framework **Bootstrap 5.3** via CDN, dikombinasikan dengan **Custom CSS** bergaya monokrom minimalis (menggunakan palette warna slate `#0f172a` dan font Google Inter). Selain itu juga menggunakan **Bootstrap Icons** untuk ikon-ikon tombol."

### Q24: Bagaimana cara kerja fitur cetak slip gaji dan simpan PDF?
**File Lokasi:** `resources/views/gaji/cetak.blade.php`
**Jawaban:**
> "Fitur cetak memanfaatkan CSS `@media print` murni dari browser:
> 1. Elemen yang tidak perlu seperti tombol aksi diberi class `.no-print` sehingga otomatis hilang saat dicetak.
> 2. Halaman diberi script `window.print()` agar saat halaman dibuka dialog printer/save PDF langsung muncul.
> 3. Format kertas dirancang menyerupai slip gaji resmi ukuran A4."

### Q25: Mengapa navbar atas tidak muncul saat di halaman Login?
**File Lokasi:** `resources/views/layouts/app.blade.php`
**Jawaban:**
> "Pada layout utama, saya memberikan pengecekan kondisi Blade:
> ```blade
> @if (!request()->routeIs('login'))
>     <nav class="navbar ..."> ... </nav>
> @endif
> ```
> Sehingga navbar hanya akan muncul di halaman selain login."

---

## KATEGORI 8: PENGUJIAN SISTEM (TESTING & PHPUNIT)

### Q26: Apakah kamu membuat pengujian otomatis (automated test)?
**File Lokasi:** `tests/Feature/SlipGajiTest.php`
**Jawaban:**
> "Ya, saya membuat unit dan feature test menggunakan **PHPUnit** bawaan Laravel yang terdiri dari 9 skenario pengujian dengan 33 assertions, dan semuanya berhasil lolos 100% (*green*)."

### Q27: Apa saja skenario yang kamu uji di PHPUnit?
**Jawaban:**
> 1. Uji akses halaman login (status 200 dan tanpa navbar).
> 2. Uji login dengan username dan password yang benar (berhasil redirect).
> 3. Uji login gagal jika password salah (muncul pesan error).
> 4. Uji proteksi middleware (tamu tanpa login ditolak mengakses slip gaji).
> 5. Uji akses halaman riwayat slip gaji setelah login.
> 6. Uji akses form tambah slip gaji.
> 7. Uji akurasi rumus perhitungan gaji bersih dan penyimpanan ke database.
> 8. Uji validasi gagal jika captcha salah.
> 9. Uji render halaman cetak slip gaji.

---

## KATEGORI 9: PERTANYAAN KRITIS / JEBAKAN ASESOR

### Q28: "Bagaimana jika koneksi internet mati? Apakah aplikasi masih bisa digunakan?"
**Jawaban:**
> "Aplikasi backend Laravel dan database MySQL berjalan di server lokal (localhost), jadi sistem inti tetap berjalan normal. Namun untuk font Google Inter dan ikon Bootstrap CDN akan beralih ke font cadangan sistem (*fallback font*) jika tidak ada internet. Jika ingin 100% offline, file CSS/JS Bootstrap tinggal kita unduh dan simpan di folder `public/` lokal."

### Q29: "Jika perusahaan memiliki ribuan karyawan, bagaimana cara mengoptimalkan performa halaman riwayat?"
**Jawaban:**
> "Bisa dioptimalkan dengan 2 cara:
> 1. Menggunakan fitur **Pagination** bawaan Laravel (`SlipGaji::paginate(10)`), sehingga data diambil per 10 atau 25 baris saja ke database.
> 2. Menambahkan **Database Index** pada kolom yang sering dicari seperti `nik` dan `nama_karyawan`."

### Q30: "Apa yang kamu lakukan jika menemukan bug atau error di aplikasi?"
**Jawaban:**
> 1. Memeriksa file log error Laravel di `storage/logs/laravel.log`.
> 2. Membaca pesan error dan nomor baris kode yang ditunjuk oleh halaman debug Laravel.
> 3. Memeriksa query database yang dieksekusi.
> 4. Menjalankan pengujian `php artisan test` untuk memastikan perbaikan tidak merusak fitur lain."

---

## KATEGORI 10: LANGKAH DEMO APLIKASI DI DEPAN ASESOR

Jika asesor menyuruhmu: *"Coba demonstrasikan aplikasimu dari awal sampai selesai!"*, ikuti urutan langkah berikut:

1. **Jelaskan Halaman Login:**
   - Tunjukkan tampilan login yang bersih tanpa navbar atas.
   - Perlihatkan fitur toggle mata (lihat/sembunyikan kata sandi).
   - Masukkan akun admin (`admin` / `admin123`).
2. **Jelaskan Halaman Riwayat:**
   - Tunjukkan 3 kartu ringkasan di atas (total slip, total uang keluar, rata-rata gaji).
   - Coba ketik nama salah satu karyawan di kolom pencarian untuk menunjukkan filter realtime.
3. **Praktikkan Tambah Slip Gaji:**
   - Klik tombol **+ Tambah Slip Gaji**.
   - Pilih periode gaji menggunakan kalender input (dari tanggal s/d tanggal).
   - Masukkan identitas karyawan (Nama, NIK, Jabatan, No WhatsApp).
   - Ketik nominal Gaji Pokok (misal: 5.000.000), Lembur (misal: 1.000.000), dan Pinjaman (misal: 500.000).
   - **Tunjukkan ke asesor:** *"Pak/Bu, saat saya mengetik angka, total penghasilan, potongan, dan gaji bersih langsung terhitung otomatis di layar."*
   - Selesaikan Captcha perkalian angka kecil, lalu klik **Simpan Slip Gaji**.
4. **Jelaskan Halaman Detail & Integrasi:**
   - Klik icon mata di baris data yang baru dibuat.
   - Tunjukkan teks terbilang otomatis (misal: *Lima Juta Lima Ratus Ribu Rupiah*).
   - Tunjukkan tanda tangan penerima di sebelah kanan.
   - Perlihatkan tombol **WhatsApp** dan **Gmail** yang langsung siap kirim.
5. **Cetak Slip Gaji:**
   - Klik tombol **Cetak / Simpan PDF**.
   - Tunjukkan bahwa dialog print browser langsung muncul dan tampilannya sangat rapi tanpa tombol-tombol yang mengganggu.
6. **Tutup dengan Logout:**
   - Klik tombol **Keluar** di navbar untuk membuktikan sesi user berhasil dihapus dengan aman.

---
*Semoga sukses dalam ujian UKK! Tunjukkan semangat, kesopanan, dan penguasaan codinganmu!*
