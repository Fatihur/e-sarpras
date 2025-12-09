<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return match ($this->type) {
                'barang_masuk' => [
                    $item->tanggal_masuk->format('d/m/Y'),
                    $item->barang->kode_barang ?? '-',
                    $item->barang->nama_barang ?? '-',
                    $item->jumlah,
                    $item->sumber_barang,
                    number_format($item->harga ?? 0, 0, ',', '.'),
                ],
                'barang_keluar' => [
                    $item->tanggal_keluar->format('d/m/Y'),
                    $item->barang->kode_barang ?? '-',
                    $item->barang->nama_barang ?? '-',
                    $item->jumlah,
                    $item->alasan_keluar,
                    $item->penerima,
                ],
                'peminjaman' => [
                    $item->tanggal_pinjam->format('d/m/Y'),
                    $item->barang->kode_barang ?? '-',
                    $item->barang->nama_barang ?? '-',
                    $item->nama_peminjam,
                    $item->status,
                    $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-',
                ],
                'barang_rusak' => [
                    $item->tanggal_rusak->format('d/m/Y'),
                    $item->barang->kode_barang ?? '-',
                    $item->barang->nama_barang ?? '-',
                    $item->jenis_kerusakan,
                    $item->lokasi,
                    $item->ruangan->nama_ruangan ?? '-',
                ],
                'barang_ruangan' => [
                    $item->ruangan->kode_ruangan ?? '-',
                    $item->ruangan->nama_ruangan ?? '-',
                    $item->barang->kode_barang ?? '-',
                    $item->barang->nama_barang ?? '-',
                    $item->jumlah,
                ],
                default => [],
            };
        });
    }

    public function headings(): array
    {
        return match ($this->type) {
            'barang_masuk' => ['Tanggal', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Sumber', 'Harga'],
            'barang_keluar' => ['Tanggal', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Alasan', 'Penerima'],
            'peminjaman' => ['Tanggal Pinjam', 'Kode Barang', 'Nama Barang', 'Peminjam', 'Status', 'Dikembalikan'],
            'barang_rusak' => ['Tanggal', 'Kode Barang', 'Nama Barang', 'Jenis Kerusakan', 'Lokasi', 'Ruangan'],
            'barang_ruangan' => ['Kode Ruangan', 'Nama Ruangan', 'Kode Barang', 'Nama Barang', 'Jumlah'],
            default => [],
        };
    }
}
