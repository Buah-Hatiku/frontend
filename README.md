# README

## Pendahuluan
Proyek ini terdiri dari dua komponen utama:
- **Frontend**: Antarmuka pengguna.
- **Backend**: Logika server dan API.

Aplikasi ini harus dijalankan menggunakan **XAMPP** karena backend berbasis PHP dan belum dihosting.

---

## Prasyarat

1. **XAMPP**:
   - Unduh dan instal XAMPP dari [https://www.apachefriends.org/](https://www.apachefriends.org/).
   - Pastikan modul **Apache** dan **MySQL** aktif.

2. **Browser**: Disarankan menggunakan Chrome atau Firefox untuk pengalaman terbaik.

3. **Database**:
   - MySQL harus diatur melalui phpMyAdmin (tersedia di XAMPP).

---

## Petunjuk Instalasi dan Penggunaan

### 1. Backend

#### Langkah-langkah:
1. Pindahkan folder `backend` ke direktori **htdocs** di XAMPP.
   - Contoh path: `C:\xampp\htdocs\backend`.
2. Buat database melalui phpMyAdmin:
   - Akses [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Buat database baru sesuai nama yang digunakan di file konfigurasi.
3. Impor file SQL:
   - Masuk ke database yang baru dibuat.
   - Gunakan fitur "Import" untuk mengunggah file SQL dari folder `backend`.
4. Pastikan konfigurasi database di file backend sudah benar:
   - Periksa file konfigurasi (misalnya, `config.php` atau sejenisnya).
   - Pastikan username, password, dan nama database sesuai dengan pengaturan XAMPP Anda.
5. Jalankan backend dengan membuka URL berikut di browser:
   - [http://localhost/backend](http://localhost/backend).

### 2. Frontend

#### Langkah-langkah:
1. Pindahkan folder `frontend` ke direktori **htdocs** di XAMPP.
   - Contoh path: `C:\xampp\htdocs\frontend`.
2. Pastikan file konfigurasi API di frontend sudah mengarah ke URL backend yang benar.
   - Contoh: `http://localhost/backend/api`.
3. Jalankan frontend dengan membuka URL berikut di browser:
   - [http://localhost/frontend](http://localhost/frontend).

---

## Troubleshooting

1. **Backend tidak berjalan**:
   - Periksa apakah modul Apache sudah aktif di XAMPP.
   - Pastikan tidak ada konflik port dengan aplikasi lain.

2. **Tidak bisa mengakses database**:
   - Pastikan modul MySQL aktif di XAMPP.
   - Cek kembali kredensial di file konfigurasi backend.

3. **Frontend tidak terhubung dengan backend**:
   - Pastikan URL backend di file konfigurasi frontend sudah benar.
   - Pastikan backend berjalan tanpa error.

---

## Catatan Tambahan
- Pastikan versi PHP kompatibel dengan backend.
- Gunakan browser terbaru untuk mendukung semua fitur frontend.

---

Terima kasih telah menggunakan aplikasi ini!

