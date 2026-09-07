# SIPENSA

SIPENSA adalah aplikasi berbasis Laravel untuk mengelola data siswa, pelanggaran, dan penentuan sanksi menggunakan Sistem Pendukung Keputusan (SPK) dengan metode FMADM dan SAW.

## Fitur utama

- Autentikasi dan pembatasan akses untuk `admin` dan `guru_bk`
- Dashboard ringkasan data
- Pengelolaan pengguna dan siswa
- Pengelolaan alternatif, kriteria, jenis pelanggaran, tingkat keterlibatan, dan jenis hukuman
- Perhitungan serta riwayat hasil SPK
- Pencarian hasil SPK berdasarkan nama, NISN, dan tanggal
- Detail perhitungan dan ekspor PDF

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- SQLite atau MySQL
- Node.js dan npm
- Vite, Tailwind CSS, Alpine.js, dan Flowbite

## Persyaratan

Pastikan perangkat sudah memiliki:

- Git
- PHP 8.2+ beserta ekstensi yang dibutuhkan Laravel, termasuk `ctype`, `curl`, `dom`, `fileinfo`, `mbstring`, `openssl`, `pdo`, dan `xml`
- Composer
- Node.js dan npm
- SQLite atau MySQL

Periksa instalasi dengan:

```bash
git --version
php --version
composer --version
node --version
npm --version
```

## Clone repository

```bash
git clone https://github.com/habibdefunc/SIPENSA.git
cd SIPENSA
```

## Instalasi project

Instal dependency backend dan frontend:

```bash
composer install
npm install
```

Buat file konfigurasi lokal dari contoh yang tersedia.

Windows Command Prompt:

```bat
copy .env.example .env
```

PowerShell, Linux, atau macOS:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Konfigurasi database

### Opsi 1: SQLite (default dan paling cepat)

Konfigurasi `.env.example` sudah menggunakan SQLite:

```env
DB_CONNECTION=sqlite
```

Buat file database jika belum tersedia.

PowerShell:

```powershell
New-Item database/database.sqlite -ItemType File -Force
```

Linux atau macOS:

```bash
touch database/database.sqlite
```

### Opsi 2: MySQL

Buat database kosong bernama `sipensa`, kemudian sesuaikan bagian database dalam `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipensa
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi MySQL masing-masing.

## Menjalankan migrasi

Untuk membuat seluruh tabel tanpa mengisi data awal:

```bash
php artisan migrate
```

Status migrasi dapat diperiksa menggunakan:

```bash
php artisan migrate:status
```

## Menjalankan seeder

Untuk mengisi data awal setelah migrasi selesai:

```bash
php artisan db:seed
```

Migrasi dan seeder juga dapat dijalankan sekaligus:

```bash
php artisan migrate --seed
```

Jika ingin menghapus seluruh tabel, membuatnya ulang, lalu menjalankan seeder:

```bash
php artisan migrate:fresh --seed
```

> **Peringatan:** `migrate:fresh` menghapus seluruh tabel beserta datanya. Gunakan hanya pada lingkungan pengembangan atau database yang aman untuk dikosongkan.

Seeder utama menjalankan:

- `AdminSeeder`
- `AlternatifSeeder`

Akun admin awal dari seeder:

```text
Username: adminsakti123
Password: adminsakti123
```

Segera ubah password tersebut jika aplikasi digunakan di luar lingkungan pengembangan.

## Menjalankan project

Cara paling praktis adalah menjalankan server Laravel dan Vite pada dua terminal terpisah.

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

Buka aplikasi melalui:

```text
http://127.0.0.1:8000
```

Sebagai alternatif, semua proses pengembangan dapat dijalankan melalui skrip Composer:

```bash
composer run dev
```

Perintah tersebut menjalankan server Laravel, queue worker, log viewer, dan Vite secara bersamaan.

## Build frontend untuk produksi

```bash
npm run build
```

Untuk deployment produksi, gunakan `.env` khusus produksi, set `APP_DEBUG=false`, lalu jalankan optimasi Laravel sesuai kebutuhan server.

## Perintah yang berguna

Membersihkan cache konfigurasi dan tampilan:

```bash
php artisan optimize:clear
```

Membuat symbolic link untuk file publik:

```bash
php artisan storage:link
```

Menjalankan test:

```bash
php artisan test
```

## Pemecahan masalah

Jika perubahan `.env` belum terbaca:

```bash
php artisan config:clear
```

Jika dependency atau autoload class bermasalah:

```bash
composer install
composer dump-autoload
```

Jika aset CSS atau JavaScript belum berubah, pastikan `npm run dev` masih berjalan atau build ulang dengan `npm run build`.

## Repository

[https://github.com/habibdefunc/SIPENSA](https://github.com/habibdefunc/SIPENSA)
