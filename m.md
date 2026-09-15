# 🎨 PANDUAN LIVE CODING & MODIFIKASI CEPAT
## Proyek: Sistem Penggajian Karyawan (UKK Penggajian)
### Khusus Persiapan Ujian: Cara Mengubah Warna, Desain, Teks, dan Logika di Depan Asesor

---

> [!IMPORTANT]
> **Trik Menghadapi Permintaan Modifikasi dari Asesor:**
> 1. Jangan panik! Tarik napas, dengarkan baik-baik apa yang diminta asesor.
> 2. Buka file yang sesuai di text editor (VS Code / Cursor).
> 3. Tekan `Ctrl + F` untuk mencari nama class atau teks yang ingin diubah.
> 4. Ubah nilainya, lalu simpan file (`Ctrl + S`).
> 5. Buka browser dan tekan `F5` / `Ctrl + R` untuk melihat perubahannya langsung!

---

## 📑 DAFTAR ISI MODIFIKASI
1. [Cara Mengubah Warna Tombol Utama (Hitam ke Biru, Hijau, dll)](#1-cara-mengubah-warna-tombol-utama)
2. [Cara Mengubah Warna Background Halaman](#2-cara-mengubah-warna-background-halaman)
3. [Cara Mengubah Teks & Ikon Nama Aplikasi di Navbar](#3-cara-mengubah-teks--ikon-nama-aplikasi-di-navbar)
4. [Cara Mengubah Warna Kotak Gaji Bersih](#4-cara-mengubah-warna-kotak-gaji-bersih)
5. [Cara Mengubah Warna Kotak Captcha](#5-cara-mengubah-warna-kotak-captcha)
6. [Cara Mengubah Warna Tombol Aksi di Tabel (Mata & Hapus)](#6-cara-mengubah-warna-tombol-aksi-di-tabel)
7. [Cara Mengubah Warna 3 Kartu Ringkasan (Stats Cards)](#7-cara-mengubah-warna-3-kartu-ringkasan)
8. [Cara Mengubah Soal Captcha (Perkalian jadi Penjumlahan)](#8-cara-mengubah-soal-captcha)
9. [Cara Mengubah Akun Login Default](#9-cara-mengubah-akun-login-default)
10. [Tabel Kode Warna Hex Populer (Bisa Langsung Copy-Paste)](#10-tabel-kode-warna-hex-populer)

---

## 1. CARA MENGUBAH WARNA TOMBOL UTAMA

Tombol utama di aplikasi kita (seperti tombol Masuk, Tambah Slip, Simpan) menggunakan class `.btn-dark-custom` dan `.btn-primary`.

**Lokasi File:**
📂 [`resources/views/layouts/app.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/layouts/app.blade.php) (sekitar baris 55 - 65)

**Codingan Asli:**
```css
.btn-dark-custom, .btn-primary {
    background-color: #0f172a; /* Warna Hitam Slate */
    border-color: #0f172a;
    color: #ffffff;
}

.btn-dark-custom:hover, .btn-primary:hover, .btn-primary:focus {
    background-color: #334155; /* Warna Hover Abu Gelap */
    border-color: #334155;
    color: #ffffff;
}
```

### 🔹 Jika Asesor Minta Warna BIRU:
Ganti dengan:
```css
.btn-dark-custom, .btn-primary {
    background-color: #2563eb; /* Biru */
    border-color: #2563eb;
    color: #ffffff;
}

.btn-dark-custom:hover, .btn-primary:hover, .btn-primary:focus {
    background-color: #1d4ed8; /* Biru Lebih Gelap */
    border-color: #1d4ed8;
    color: #ffffff;
}
```

### 🔹 Jika Asesor Minta Warna HIJAU (Emerald):
Ganti dengan:
```css
.btn-dark-custom, .btn-primary {
    background-color: #059669; /* Hijau */
    border-color: #059669;
    color: #ffffff;
}

.btn-dark-custom:hover, .btn-primary:hover, .btn-primary:focus {
    background-color: #047857; /* Hijau Lebih Gelap */
    border-color: #047857;
    color: #ffffff;
}
```

---

## 2. CARA MENGUBAH WARNA BACKGROUND HALAMAN

**Lokasi File:**
📂 [`resources/views/layouts/app.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/layouts/app.blade.php) (sekitar baris 19 - 26)

**Codingan Asli:**
```css
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: #f8fafc; /* Abu-abu sangat lembut */
    color: #1e293b;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
```

### 🔹 Pilihan Modifikasi:
- **Putih Bersih:** Ganti `#f8fafc` menjadi `#ffffff`
- **Biru Sangat Muda (Soft Blue):** Ganti `#f8fafc` menjadi `#f0f9ff`
- **Hijau Sangat Muda (Soft Green):** Ganti `#f8fafc` menjadi `#f0fdf4`
- **Abu-abu Sedang:** Ganti `#f8fafc` menjadi `#e2e8f0`

---

## 3. CARA MENGUBAH TEKS & IKON NAMA APLIKASI DI NAVBAR

**Lokasi File:**
📂 [`resources/views/layouts/app.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/layouts/app.blade.php) (sekitar baris 137 - 140)

**Codingan Asli:**
```html
<a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('slip-gaji.index') }}">
    <i class="bi bi-wallet2 text-dark fs-5"></i>
    <span>UKK PENGGAJIAN</span>
</a>
```

### 🔹 Cara Mengubah Nama Perusahaan / Judul:
Ganti tulisan `UKK PENGGAJIAN` menjadi nama yang diminta asesor, contoh:
```html
<span>PT MAJU BERSAMA - PENGGAJIAN</span>
```

### 🔹 Cara Mengubah Ikon:
Ganti class ikon `bi-wallet2` dengan ikon Bootstrap lainnya:
- Ikon Uang Kertas: `bi-cash-stack`
- Ikon Gedung / Kantor: `bi-building`
- Ikon Orang / Karyawan: `bi-people`
- Ikon Dokumen: `bi-file-earmark-text`

Contoh:
```html
<i class="bi bi-cash-stack text-success fs-5"></i>
```

---

## 4. CARA MENGUBAH WARNA KOTAK GAJI BERSIH

Kotak sorotan total gaji bersih yang muncul di Form Tambah dan Detail Slip.

**Lokasi File:**
📂 [`resources/views/layouts/app.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/layouts/app.blade.php) (sekitar baris 97 - 102)

**Codingan Asli:**
```css
.box-highlight {
    background-color: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 1.25rem;
}
```

### 🔹 Jika Ingin Diubah Jadi Nuansa Hijau (Uang Sukses):
```css
.box-highlight {
    background-color: #ecfdf5; /* Hijau Pastel */
    border: 1px solid #a7f3d0;
    color: #065f46;
    border-radius: 8px;
    padding: 1.25rem;
}
```

### 🔹 Jika Ingin Diubah Jadi Nuansa Biru Modern:
```css
.box-highlight {
    background-color: #eff6ff; /* Biru Pastel */
    border: 1px solid #bfdbfe;
    color: #1e40af;
    border-radius: 8px;
    padding: 1.25rem;
}
```

---

## 5. CARA MENGUBAH WARNA KOTAK CAPTCHA

Kotak hitam tempat soal perkalian captcha ditampilkan.

**Lokasi File:**
📂 [`resources/views/layouts/app.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/layouts/app.blade.php) (sekitar baris 85 - 95)

**Codingan Asli:**
```css
.captcha-box {
    background-color: #0f172a; /* Hitam Slate */
    color: #f8fafc;
    font-size: 1.15rem;
    font-weight: 700;
    letter-spacing: 2px;
    padding: 0.5rem 1.25rem;
    border-radius: 6px;
    user-select: none;
    display: inline-block;
}
```

### 🔹 Ubah Jadi Warna Merah Bold:
Ganti `background-color: #0f172a;` menjadi:
```css
background-color: #dc2626; /* Merah */
```

### 🔹 Ubah Jadi Warna Oranye / Amber:
```css
background-color: #d97706; /* Oranye */
```

---

## 6. CARA MENGUBAH WARNA TOMBOL AKSI DI TABEL

Di tabel riwayat slip gaji, terdapat 2 tombol aksi: **Detail (Mata)** dan **Hapus (Tong Sampah)**.

**Lokasi File:**
📂 [`resources/views/gaji/index.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/gaji/index.blade.php) (sekitar baris 90 - 105)

**Codingan Asli:**
```blade
<!-- Tombol Detail (Mata) -->
<a href="{{ route('slip-gaji.show', $slip->id) }}" class="btn btn-outline-secondary" title="Lihat Rincian">
    <i class="bi bi-eye"></i>
</a>

<!-- Tombol Hapus -->
<button type="button" class="btn btn-outline-danger" title="Hapus" ...>
    <i class="bi bi-trash"></i>
</button>
```

### 🔹 Modifikasi Tombol Detail (Mata):
- Jika ingin warna **Biru Solid**: ganti `btn-outline-secondary` jadi `btn-primary`
- Jika ingin garis tepi **Biru**: ganti `btn-outline-secondary` jadi `btn-outline-primary`
- Jika ingin warna **Hijau**: ganti `btn-outline-secondary` jadi `btn-outline-success`

### 🔹 Modifikasi Tombol Hapus:
- Jika ingin warna **Merah Solid**: ganti `btn-outline-danger` jadi `btn-danger text-white`

---

## 7. CARA MENGUBAH WARNA 3 KARTU RINGKASAN

Di bagian atas halaman riwayat ada 3 kartu: *Total Dokumen Slip*, *Total Pengeluaran Gaji*, dan *Rata-rata Gaji*.

**Lokasi File:**
📂 [`resources/views/gaji/index.blade.php`](file:///home/rus/Rustaman/tugas/ukkrus/resources/views/gaji/index.blade.php) (sekitar baris 22 - 60)

### 🔹 Ingin Kartu Berwarna-warni?
Ubah class `<div class="card p-3">` menjadi class bawaan Bootstrap berikut:
1. **Kartu 1 (Biru Lembut):**
   ```blade
   <div class="card p-3 bg-primary-subtle border-primary-subtle">
   ```
2. **Kartu 2 (Hijau Lembut):**
   ```blade
   <div class="card p-3 bg-success-subtle border-success-subtle">
   ```
3. **Kartu 3 (Kuning Lembut):**
   ```blade
   <div class="card p-3 bg-warning-subtle border-warning-subtle">
   ```

---

## 8. CARA MENGUBAH SOAL CAPTCHA

Jika asesor bertanya: *"Coba ubah captcha dari perkalian jadi penjumlahan!"*

**Lokasi File:**
📂 [`app/Http/Controllers/SlipGajiController.php`](file:///home/rus/Rustaman/tugas/ukkrus/app/Http/Controllers/SlipGajiController.php)

Cari method `create()` (sekitar baris 35 - 45):
**Codingan Asli (Perkalian):**
```php
$angka1 = rand(2, 9);
$angka2 = rand(2, 5);
$jawaban = $angka1 * $angka2;
$soalCaptcha = "{$angka1} × {$angka2} = ?";
```

**Ubah Menjadi Penjumlahan:**
```php
$angka1 = rand(5, 20);
$angka2 = rand(5, 20);
$jawaban = $angka1 + $angka2;
$soalCaptcha = "{$angka1} + {$angka2} = ?";
```

Simpan file, lalu buka form tambah slip gaji, soal captcha otomatis berubah menjadi penjumlahan!

---

## 9. CARA MENGUBAH AKUN LOGIN DEFAULT

Jika asesor meminta: *"Coba ubah password atau tambahkan user baru!"*

**Lokasi File:**
📂 [`database/seeders/DatabaseSeeder.php`](file:///home/rus/Rustaman/tugas/ukkrus/database/seeders/DatabaseSeeder.php) (sekitar baris 17 - 25)

**Codingan Asli:**
```php
User::create([
    'username' => 'admin',
    'password' => Hash::make('admin123'),
    'nama_lengkap' => 'Administrator Sistem',
    'email' => 'admin@gmail.com',
    'role' => 'admin',
]);
```

Tinggal ganti username atau password di atas, lalu jalankan di terminal:
```bash
php artisan db:seed
```

---

## 10. TABEL KODE WARNA HEX POPULER

Jika asesor meminta warna tertentu, kamu tinggal memilih kode hex di bawah ini:

| Nama Warna | Kode Hex Utama | Kode Hex Hover (Lebih Gelap) | Cocok Untuk |
| :--- | :--- | :--- | :--- |
| **Dark Slate (Asli)** | `#0f172a` | `#334155` | Tombol utama, navbar, footer |
| **Biru Modern** | `#2563eb` | `#1d4ed8` | Tombol simpan, link, badge |
| **Biru Indigo** | `#4f46e5` | `#4338ca` | Tombol elegan |
| **Hijau Emerald** | `#059669` | `#047857` | Sukses, cetak, total gaji |
| **Merah Crimson** | `#dc2626` | `#b91c1c` | Tombol hapus, peringatan |
| **Oranye / Amber** | `#d97706` | `#b45309` | Peringatan, highlight |
| **Ungu Elegan** | `#7c3aed` | `#6d28d9` | Tombol fitur khusus |
| **Abu-abu Terang** | `#f8fafc` | `#e2e8f0` | Background body |
| **Putih Bersih** | `#ffffff` | `#f1f5f9` | Card background |

---

## 💡 RANGKUMAN LOKASI FILE PENTING

| Yang Ingin Diubah | File Target |
| :--- | :--- |
| Warna tombol, background body, navbar, kotak captcha | `resources/views/layouts/app.blade.php` |
| Halaman login (form, input, teks) | `resources/views/auth/login.blade.php` |
| Tabel riwayat, kartu ringkasan, pencarian | `resources/views/gaji/index.blade.php` |
| Form input gaji, kalender periode, script kalkulasi JS | `resources/views/gaji/create.blade.php` |
| Detail slip, tombol WhatsApp, Gmail, ttd penerima | `resources/views/gaji/show.blade.php` |
| Kertas cetak PDF, CSS @media print | `resources/views/gaji/cetak.blade.php` |
| Rumus hitung gaji backend, logika captcha | `app/Http/Controllers/SlipGajiController.php` |
| Logika login, validasi user | `app/Http/Controllers/AuthController.php` |
| Format rupiah, teks terbilang, url WA/Email | `app/Models/SlipGaji.php` |
| Route web dan middleware | `routes/web.php` |

---
*Simpan file ini dan buka kapan saja saat membutuhkan contekan cepat modifikasi di depan asesor!*
