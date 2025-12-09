# Sistem Manajemen Inventaris & Aset

Aplikasi inventaris berbasis Laravel 12 dengan fitur QR Code dan notifikasi Telegram.

## Fitur Utama
- Master Data: Barang, Kategori, Ruangan, Gedung, Lahan
- Transaksi: Barang Masuk, Barang Keluar, Peminjaman, Barang Rusak
- QR Code Generator & Scanner
- Notifikasi Telegram otomatis
- Laporan dengan export PDF & Excel
- Multi-role: Admin, Manajemen, Pimpinan

## Instalasi

1. **Buat database MySQL:**
```sql
CREATE DATABASE inventaris_db;
```

2. **Konfigurasi .env:**
```
DB_DATABASE=inventaris_db
DB_USERNAME=root
DB_PASSWORD=
```

3. **Jalankan migration & seeder:**
```bash
php artisan migrate
php artisan db:seed
```

4. **Jalankan aplikasi:**
```bash
php artisan serve
```

5. **Akses aplikasi:** http://localhost:8000

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@inventaris.com | password |
| Manajemen | manajemen@inventaris.com | password |
| Pimpinan | pimpinan@inventaris.com | password |

## Konfigurasi Telegram

1. Login sebagai Admin
2. Buka menu Telegram
3. Masukkan Bot Token dan Group ID
4. Aktifkan notifikasi yang diinginkan
