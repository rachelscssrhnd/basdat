# Basdat - Sistem Informasi Laboratorium (Laravel)

Repository GitHub: https://github.com/rachelscssrhnd/basdat

## Prasyarat

Pastikan sudah ter-install:

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL/MariaDB

## Setup Project

Jalankan perintah berikut di folder project (`basdat/`):

1) Install dependency backend

```bash
composer install
```

2) Install dependency frontend

```bash
npm install
```

3) Buat file environment

Project ini membutuhkan file `.env`.

- Jika ada `.env.example`, copy menjadi `.env`.
- Jika tidak ada, buat file `.env` baru lalu isi minimal seperti contoh di bawah ini.

Contoh konfigurasi (sesuaikan user/password/nama database di komputer kamu):

```env
APP_NAME="Basdat"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=basdat
DB_USERNAME=root
DB_PASSWORD=

# Koneksi database warehouse untuk fitur Analytics (Admin Dashboard)
DB_WAREHOUSE_HOST=127.0.0.1
DB_WAREHOUSE_PORT=3306
DB_WAREHOUSE_DATABASE=basdat_warehouse
DB_WAREHOUSE_USERNAME=root
DB_WAREHOUSE_PASSWORD=
```

4) Generate application key

```bash
php artisan key:generate
```

5) Jalankan migration + seeder

```bash
php artisan migrate --seed
```

## Menjalankan Aplikasi

### Opsi A (disarankan): 1 perintah

Jalankan server Laravel + Vite (frontend) sekaligus:

```bash
composer run dev
```

Aplikasi akan berjalan di:

- `http://127.0.0.1:8000`

### Opsi B: manual (2 terminal)

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

## Akun Default (Seeder)

Seeder membuat akun:

- Admin
  - Username: `admin`
  - Password: `admin123`
- User/Pasien
  - Username: `user`
  - Password: `user123`

## URL Penting

- Home: `http://127.0.0.1:8000/`
- Login: `http://127.0.0.1:8000/login`
- Admin Dashboard: `http://127.0.0.1:8000/admin`

## Catatan Database Warehouse (Analytics)

Fitur analytics di Admin Dashboard mengambil data dari koneksi database `warehouse` (lihat `config/database.php`).

Jika database warehouse belum disiapkan:

- Halaman admin tetap bisa dibuka.
- Bagian chart/analytics bisa gagal memuat data (karena query ke koneksi `warehouse`).

Solusi:

- Buat database warehouse sesuai kebutuhan tugas dan isi tabel dimensi/fakta.
- Pastikan variabel `.env` `DB_WAREHOUSE_*` sudah benar.

## Troubleshooting

- Jika error terkait `.env` atau `APP_KEY`:
  - Pastikan `.env` ada.
  - Jalankan `php artisan key:generate`.

- Jika error koneksi database:
  - Pastikan MySQL aktif.
  - Cek `DB_*` dan `DB_WAREHOUSE_*` di `.env`.

- Jika CSS/JS tidak muncul:
  - Pastikan `npm install` sudah dijalankan.
  - Jalankan `npm run dev` atau gunakan `composer run dev`.
