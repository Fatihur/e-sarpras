<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeseluruhanExport implements WithMultipleSheets
{
    protected $barangMasuk;
    protected $barangKeluar;
    protected $peminjaman;
    protected $barangRusak;
    protected $barangRuangan;

    public function __construct($barangMasuk, $barangKeluar, $peminjaman, $barangRusak, $barangRuangan)
    {
        $this->barangMasuk = $barangMasuk;
        $this->barangKeluar = $barangKeluar;
        $this->peminjaman = $peminjaman;
        $this->barangRusak = $barangRusak;
        $this->barangRuangan = $barangRuangan;
    }

    public function sheets(): array
    {
        return [
            'Barang Masuk' => new LaporanExport($this->barangMasuk, 'barang_masuk'),
            'Barang Keluar' => new LaporanExport($this->barangKeluar, 'barang_keluar'),
            'Peminjaman' => new LaporanExport($this->peminjaman, 'peminjaman'),
            'Barang Rusak' => new LaporanExport($this->barangRusak, 'barang_rusak'),
            'Barang Ruangan' => new LaporanExport($this->barangRuangan, 'barang_ruangan'),
        ];
    }
}
