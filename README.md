# SIPENSA

SIPENSA adalah aplikasi web Laravel untuk membantu pengelolaan data siswa dan proses penentuan sanksi/pelanggaran dengan pendekatan SPK FMADM dan SAW.

## Fitur Utama

- Login dan hak akses `admin` dan `guru bk`
- Dashboard ringkas dengan statistik data penting
- CRUD data user
- CRUD data siswa
- CRUD data alternatif untuk proses SPK
- CRUD data kriteria
- CRUD data jenis pelanggaran
- CRUD data tingkat keterlibatan
- CRUD data jenis hukuman
- Proses SPK dengan hasil perhitungan fuzzy
- Detail hasil SPK
- Cetak hasil SPK ke PDF
- Komponen Blade reusable untuk datatable, modal, search, dan notifikasi

## Kebutuhan Sistem

- PHP 8.2+
- Composer
- Node.js dan npm
- MySQL

## Clone Project

```bash
git clone https://github.com/habibdefunc/SIPENSA.git
cd SIPENSA
```

## Instalasi

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Lalu sesuaikan konfigurasi database di file `.env`.

## Migrasi dan Seeder

```bash
php artisan migrate:fresh --seed
```

## Menjalankan Aplikasi

```bash
php artisan serve
npm run dev
```

Setelah itu buka aplikasi melalui alamat yang tampil di terminal, biasanya:

```bash
http://127.0.0.1:8000
```

## Catatan

- Akun admin dan guru BK mengikuti data seeder yang tersedia.
- Jika ada perubahan schema, jalankan ulang migrasi sesuai kebutuhan proyek.
