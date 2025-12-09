<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Models\Lahan;
use App\Models\PengaturanTelegram;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'aktif' => true,
        ]);

        User::create([
            'nama' => 'Staff Manajemen',
            'email' => 'manajemen@inventaris.com',
            'password' => Hash::make('password'),
            'role' => 'manajemen',
            'aktif' => true,
        ]);

        User::create([
            'nama' => 'Pimpinan',
            'email' => 'pimpinan@inventaris.com',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'aktif' => true,
        ]);

        // Kategori
        $kategori = [
            ['nama_kategori' => 'Elektronik', 'deskripsi_kategori' => 'Peralatan elektronik'],
            ['nama_kategori' => 'Furniture', 'deskripsi_kategori' => 'Meja, kursi, lemari'],
            ['nama_kategori' => 'ATK', 'deskripsi_kategori' => 'Alat tulis kantor'],
            ['nama_kategori' => 'Kendaraan', 'deskripsi_kategori' => 'Kendaraan operasional'],
        ];
        foreach ($kategori as $k) {
            Kategori::create($k);
        }


        // Lahan (kode auto-generate)
        Lahan::create(['nama_lahan' => 'Lahan Utama', 'lokasi_lahan' => 'Jl. Utama No. 1']);

        // Gedung
        Gedung::create(['nama_gedung' => 'Gedung A', 'alamat_gedung' => 'Lantai 1-3', 'lahan_id' => 1]);
        Gedung::create(['nama_gedung' => 'Gedung B', 'alamat_gedung' => 'Lantai 1-2', 'lahan_id' => 1]);

        // Ruangan (kode auto-generate)
        $ruanganList = [
            ['nama_ruangan' => 'Ruang Rapat', 'gedung_id' => 1, 'penanggung_jawab' => 'Budi'],
            ['nama_ruangan' => 'Ruang IT', 'gedung_id' => 1, 'penanggung_jawab' => 'Andi'],
            ['nama_ruangan' => 'Ruang Direktur', 'gedung_id' => 1, 'penanggung_jawab' => 'Siti'],
            ['nama_ruangan' => 'Gudang', 'gedung_id' => 2, 'penanggung_jawab' => 'Joko'],
        ];
        foreach ($ruanganList as $r) {
            Ruangan::create($r);
        }

        // Pengaturan Telegram
        PengaturanTelegram::create([
            'bot_token' => '',
            'group_id' => '',
            'notif_peminjaman' => true,
            'notif_pengembalian' => true,
            'notif_barang_rusak' => true,
            'notif_barang_masuk' => false,
            'notif_barang_keluar' => false,
        ]);

        // Sample Barang (kode & QR auto-generate)
        $barangList = [
            ['nama_barang' => 'Laptop Dell', 'kategori_id' => 1, 'satuan' => 'unit', 'jumlah' => 5, 'nilai_aset' => 15000000],
            ['nama_barang' => 'Proyektor Epson', 'kategori_id' => 1, 'satuan' => 'unit', 'jumlah' => 2, 'nilai_aset' => 8000000],
            ['nama_barang' => 'Meja Kerja', 'kategori_id' => 2, 'satuan' => 'unit', 'jumlah' => 20, 'nilai_aset' => 1500000],
            ['nama_barang' => 'Kursi Kantor', 'kategori_id' => 2, 'satuan' => 'unit', 'jumlah' => 30, 'nilai_aset' => 800000],
            ['nama_barang' => 'Printer HP', 'kategori_id' => 1, 'satuan' => 'unit', 'jumlah' => 3, 'nilai_aset' => 3500000],
        ];

        foreach ($barangList as $b) {
            Barang::create($b);
        }
    }
}
