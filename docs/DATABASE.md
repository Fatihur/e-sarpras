# Dokumentasi Database e-Sarpras

## Deskripsi Sistem
e-Sarpras adalah sistem manajemen sarana dan prasarana untuk pesantren yang mencakup pengelolaan barang, ruangan, gedung, lahan, peminjaman, dan pelaporan kerusakan.

## Entity Relationship Diagram (ERD)

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│   LAHAN     │       │   GEDUNG    │       │   RUANGAN   │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │◄──────│ lahan_id(FK)│       │ id (PK)     │
│ kode_lahan  │       │ id (PK)     │◄──────│ gedung_id   │
│ nama_lahan  │       │ nama_gedung │       │ kode_ruangan│
│ lokasi_lahan│       │ alamat_gedung       │ nama_ruangan│
└─────────────┘       └─────────────┘       │ penanggung  │
                                            │ keterangan  │
                                            └──────┬──────┘
                                                   │
┌─────────────┐       ┌─────────────┐              │
│  KATEGORI   │       │   BARANG    │              │
├─────────────┤       ├─────────────┤              │
│ id (PK)     │◄──────│ kategori_id │              │
│ nama_kategori       │ id (PK)     │              │
│ deskripsi   │       │ kode_barang │              │
└─────────────┘       │ nama_barang │              │
                      │ satuan      │              │
                      │ jumlah      │              │
                      │ nilai_aset  │              │
                      │ status_barang              │
                      │ foto_barang │              │
                      └──────┬──────┘              │
                             │                     │
              ┌──────────────┼──────────────┐      │
              │              │              │      │
              ▼              ▼              ▼      ▼
┌─────────────────┐ ┌───────────────┐ ┌─────────────────┐
│  BARANG_MASUK   │ │ BARANG_KELUAR │ │ BARANG_RUANGAN  │
├─────────────────┤ ├───────────────┤ ├─────────────────┤
│ id (PK)         │ │ id (PK)       │ │ id (PK)         │
│ barang_id (FK)  │ │ barang_id(FK) │ │ barang_id (FK)  │
│ tanggal_masuk   │ │ tanggal_keluar│ │ ruangan_id (FK) │
│ jumlah          │ │ jumlah        │ │ jumlah          │
│ sumber_barang   │ │ alasan_keluar │ │ keterangan      │
│ harga           │ │ penerima      │ └─────────────────┘
│ catatan         │ │ catatan       │
│ user_id (FK)    │ │ user_id (FK)  │
└─────────────────┘ └───────────────┘

┌─────────────────┐ ┌─────────────────┐
│   PEMINJAMAN    │ │  BARANG_RUSAK   │
├─────────────────┤ ├─────────────────┤
│ id (PK)         │ │ id (PK)         │
│ barang_id (FK)  │ │ barang_id (FK)  │
│ nama_peminjam   │ │ ruangan_id (FK) │
│ kontak_peminjam │ │ tanggal_rusak   │
│ tanggal_pinjam  │ │ jenis_kerusakan │
│ tanggal_kembali │ │ deskripsi       │
│ tanggal_dikemba │ │ foto_bukti      │
│ status          │ │ lokasi          │
│ keterangan      │ │ status          │
│ user_id (FK)    │ │ catatan_status  │
└─────────────────┘ │ tanggal_update  │
                    │ user_id (FK)    │
                    └─────────────────┘

┌─────────────┐     ┌───────────────────┐     ┌─────────────────┐
│   USERS     │     │PENGATURAN_TELEGRAM│     │ LOG_NOTIFIKASI  │
├─────────────┤     ├───────────────────┤     ├─────────────────┤
│ id (PK)     │     │ id (PK)           │     │ id (PK)         │
│ nama        │     │ bot_token         │     │ tipe_notifikasi │
│ email       │     │ group_id          │     │ pesan           │
│ password    │     │ notif_peminjaman  │     │ status          │
│ role        │     │ notif_pengembalian│     │ response        │
│ aktif       │     │ notif_barang_rusak│     │ waktu_kirim     │
│ remember_tok│     │ notif_barang_masuk│     └─────────────────┘
└─────────────┘     │ notif_barang_kelua│
                    └───────────────────┘
```

---

## Struktur Tabel

### 1. users
Menyimpan data pengguna sistem.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik user |
| nama | VARCHAR(255) | NOT NULL | Nama lengkap |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Email login |
| password | VARCHAR(255) | NOT NULL | Password (hashed) |
| role | ENUM | DEFAULT 'manajemen' | admin, manajemen, pimpinan |
| aktif | BOOLEAN | DEFAULT true | Status aktif user |
| remember_token | VARCHAR(100) | NULLABLE | Token remember me |

### 2. lahan
Menyimpan data lahan/area.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik lahan |
| kode_lahan | VARCHAR(255) | UNIQUE | Kode identifikasi lahan |
| nama_lahan | VARCHAR(255) | NOT NULL | Nama lahan |
| lokasi_lahan | VARCHAR(255) | NULLABLE | Lokasi/alamat lahan |

### 3. gedung
Menyimpan data gedung.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik gedung |
| nama_gedung | VARCHAR(255) | NOT NULL | Nama gedung |
| alamat_gedung | TEXT | NULLABLE | Alamat lengkap gedung |
| lahan_id | BIGINT | FK → lahan.id | Referensi ke lahan |

### 4. ruangan
Menyimpan data ruangan dalam gedung.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik ruangan |
| kode_ruangan | VARCHAR(255) | UNIQUE | Kode identifikasi ruangan |
| nama_ruangan | VARCHAR(255) | NOT NULL | Nama ruangan |
| gedung_id | BIGINT | FK → gedung.id | Referensi ke gedung |
| penanggung_jawab | VARCHAR(255) | NULLABLE | Nama penanggung jawab |
| keterangan | TEXT | NULLABLE | Keterangan tambahan |

### 5. kategori
Menyimpan kategori barang.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik kategori |
| nama_kategori | VARCHAR(255) | NOT NULL | Nama kategori |
| deskripsi_kategori | TEXT | NULLABLE | Deskripsi kategori |

### 6. barang
Menyimpan data barang inventaris.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik barang |
| kode_barang | VARCHAR(255) | UNIQUE | Kode inventaris (auto-generate) |
| nama_barang | VARCHAR(255) | NOT NULL | Nama barang |
| kategori_id | BIGINT | FK → kategori.id | Referensi ke kategori |
| satuan | VARCHAR(255) | DEFAULT 'unit' | Satuan barang |
| jumlah | INTEGER | DEFAULT 0 | Jumlah stok |
| nilai_aset | DECIMAL(15,2) | DEFAULT 0 | Nilai aset dalam Rupiah |
| status_barang | ENUM | DEFAULT 'aktif' | aktif, rusak, hilang, keluar, dipinjam |
| foto_barang | VARCHAR(255) | NULLABLE | Path foto barang |

### 7. barang_ruangan (Pivot Table)
Relasi many-to-many antara barang dan ruangan.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik |
| barang_id | BIGINT | FK → barang.id | Referensi ke barang |
| ruangan_id | BIGINT | FK → ruangan.id | Referensi ke ruangan |
| jumlah | INTEGER | DEFAULT 1 | Jumlah barang di ruangan |
| keterangan | TEXT | NULLABLE | Keterangan tambahan |

### 8. barang_masuk
Menyimpan transaksi barang masuk.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik transaksi |
| tanggal_masuk | DATE | NOT NULL | Tanggal barang masuk |
| barang_id | BIGINT | FK → barang.id | Referensi ke barang |
| jumlah | INTEGER | NOT NULL | Jumlah barang masuk |
| sumber_barang | VARCHAR(255) | NULLABLE | Asal/sumber barang |
| harga | DECIMAL(15,2) | NULLABLE | Harga per unit |
| catatan | TEXT | NULLABLE | Catatan tambahan |
| user_id | BIGINT | FK → users.id | User yang input |

### 9. barang_keluar
Menyimpan transaksi barang keluar.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik transaksi |
| tanggal_keluar | DATE | NOT NULL | Tanggal barang keluar |
| barang_id | BIGINT | FK → barang.id | Referensi ke barang |
| jumlah | INTEGER | NOT NULL | Jumlah barang keluar |
| alasan_keluar | VARCHAR(255) | NOT NULL | Alasan pengeluaran |
| penerima | VARCHAR(255) | NULLABLE | Nama penerima |
| catatan | TEXT | NULLABLE | Catatan tambahan |
| user_id | BIGINT | FK → users.id | User yang input |

### 10. peminjaman
Menyimpan data peminjaman barang.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik peminjaman |
| barang_id | BIGINT | FK → barang.id | Referensi ke barang |
| nama_peminjam | VARCHAR(255) | NOT NULL | Nama peminjam |
| kontak_peminjam | VARCHAR(255) | NULLABLE | Kontak peminjam |
| tanggal_pinjam | DATE | NOT NULL | Tanggal pinjam |
| tanggal_kembali | DATE | NULLABLE | Estimasi tanggal kembali |
| tanggal_dikembalikan | DATE | NULLABLE | Tanggal aktual dikembalikan |
| status | ENUM | DEFAULT 'dipinjam' | dipinjam, dikembalikan |
| keterangan | TEXT | NULLABLE | Keterangan tambahan |
| user_id | BIGINT | FK → users.id | User yang input |

### 11. barang_rusak
Menyimpan laporan kerusakan barang.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik laporan |
| barang_id | BIGINT | FK → barang.id | Referensi ke barang |
| ruangan_id | BIGINT | FK → ruangan.id | Lokasi ruangan (jika ada) |
| tanggal_rusak | DATE | NOT NULL | Tanggal kerusakan |
| jenis_kerusakan | VARCHAR(255) | NOT NULL | Jenis kerusakan |
| deskripsi_kerusakan | TEXT | NULLABLE | Deskripsi detail |
| foto_bukti | VARCHAR(255) | NULLABLE | Path foto bukti |
| lokasi | ENUM | DEFAULT 'dalam_ruangan' | dalam_ruangan, luar_ruangan |
| status | ENUM | DEFAULT 'dilaporkan' | dilaporkan, diproses, diperbaiki, tidak_bisa_diperbaiki |
| catatan_status | TEXT | NULLABLE | Catatan update status |
| tanggal_update_status | TIMESTAMP | NULLABLE | Waktu update status |
| user_id | BIGINT | FK → users.id | User yang melaporkan |

### 12. pengaturan_telegram
Menyimpan konfigurasi notifikasi Telegram.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik |
| bot_token | VARCHAR(255) | NULLABLE | Token bot Telegram |
| group_id | VARCHAR(255) | NULLABLE | ID grup Telegram |
| notif_peminjaman | BOOLEAN | DEFAULT true | Notif peminjaman aktif |
| notif_pengembalian | BOOLEAN | DEFAULT true | Notif pengembalian aktif |
| notif_barang_rusak | BOOLEAN | DEFAULT true | Notif kerusakan aktif |
| notif_barang_masuk | BOOLEAN | DEFAULT false | Notif barang masuk aktif |
| notif_barang_keluar | BOOLEAN | DEFAULT false | Notif barang keluar aktif |

### 13. log_notifikasi
Menyimpan log pengiriman notifikasi.

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID unik log |
| tipe_notifikasi | VARCHAR(255) | NOT NULL | Tipe notifikasi |
| pesan | TEXT | NOT NULL | Isi pesan |
| status | ENUM | DEFAULT 'terkirim' | terkirim, gagal |
| response | TEXT | NULLABLE | Response dari API |
| waktu_kirim | DATETIME | NOT NULL | Waktu pengiriman |

---

## Relasi Antar Tabel

| Tabel Asal | Relasi | Tabel Tujuan | Deskripsi |
|------------|--------|--------------|-----------|
| gedung | belongsTo | lahan | Gedung berada di lahan |
| ruangan | belongsTo | gedung | Ruangan berada di gedung |
| barang | belongsTo | kategori | Barang memiliki kategori |
| barang | belongsToMany | ruangan | Barang bisa di banyak ruangan (via barang_ruangan) |
| barang_masuk | belongsTo | barang | Transaksi masuk untuk barang |
| barang_masuk | belongsTo | users | Dicatat oleh user |
| barang_keluar | belongsTo | barang | Transaksi keluar untuk barang |
| barang_keluar | belongsTo | users | Dicatat oleh user |
| peminjaman | belongsTo | barang | Peminjaman barang |
| peminjaman | belongsTo | users | Dicatat oleh user |
| barang_rusak | belongsTo | barang | Laporan kerusakan barang |
| barang_rusak | belongsTo | ruangan | Lokasi kerusakan |
| barang_rusak | belongsTo | users | Dilaporkan oleh user |

---

## Indeks

- `users.email` - UNIQUE INDEX
- `lahan.kode_lahan` - UNIQUE INDEX
- `ruangan.kode_ruangan` - UNIQUE INDEX
- `barang.kode_barang` - UNIQUE INDEX
- Foreign keys otomatis membuat index
