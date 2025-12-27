<?php

namespace App\Services;

use App\Models\LogNotifikasi;
use App\Models\PengaturanTelegram;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected ?PengaturanTelegram $pengaturan;

    public function __construct()
    {
        $this->pengaturan = PengaturanTelegram::first();
    }

    public function kirimNotifikasi(string $tipe, string $pesan): bool
    {
        if (!$this->pengaturan || !$this->pengaturan->bot_token || !$this->pengaturan->group_id) {
            return false;
        }

        if (!$this->cekNotifikasiAktif($tipe)) {
            return false;
        }

        try {
            $url = "https://api.telegram.org/bot{$this->pengaturan->bot_token}/sendMessage";

            $response = Http::post($url, [
                'chat_id' => $this->pengaturan->group_id,
                'text' => $pesan,
                'parse_mode' => 'HTML',
            ]);

            $status = $response->successful() ? 'terkirim' : 'gagal';

            LogNotifikasi::create([
                'tipe_notifikasi' => $tipe,
                'pesan' => $pesan,
                'status' => $status,
                'response' => $response->body(),
                'waktu_kirim' => now(),
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            LogNotifikasi::create([
                'tipe_notifikasi' => $tipe,
                'pesan' => $pesan,
                'status' => 'gagal',
                'response' => $e->getMessage(),
                'waktu_kirim' => now(),
            ]);
            return false;
        }
    }

    protected function cekNotifikasiAktif(string $tipe): bool
    {
        return match ($tipe) {
            'peminjaman' => $this->pengaturan->notif_peminjaman,
            'pengembalian' => $this->pengaturan->notif_pengembalian,
            'barang_rusak' => $this->pengaturan->notif_barang_rusak,
            'barang_masuk' => $this->pengaturan->notif_barang_masuk,
            'barang_keluar' => $this->pengaturan->notif_barang_keluar,
            'test' => true, // Test notifikasi selalu diizinkan
            default => false,
        };
    }

    public function notifPeminjaman($peminjaman): void
    {
        $waktu = now()->format('d/m/Y H:i');
        $rencanaKembali = $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : 'Belum ditentukan';

        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "📦 <b>PEMINJAMAN BARANG</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🏷 <b>Detail Barang</b>\n"
            . "├ Nama: <code>{$peminjaman->barang->nama_barang}</code>\n"
            . "├ Kode: <code>{$peminjaman->barang->kode_barang}</code>\n"
            . "└ Jumlah: {$peminjaman->jumlah} unit\n\n"
            . "👤 <b>Peminjam</b>\n"
            . "├ Nama: {$peminjaman->nama_peminjam}\n"
            . "└ Keperluan: {$peminjaman->keperluan}\n\n"
            . "📅 <b>Jadwal</b>\n"
            . "├ Tgl Pinjam: {$peminjaman->tanggal_pinjam->format('d/m/Y')}\n"
            . "└ Rencana Kembali: {$rencanaKembali}\n\n"
            . "🕐 <i>Dicatat: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";

        $this->kirimNotifikasi('peminjaman', $pesan);
    }

    public function notifPengembalian($peminjaman): void
    {
        $waktu = now()->format('d/m/Y H:i');
        $durasi = $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_dikembalikan);

        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "✅ <b>PENGEMBALIAN BARANG</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🏷 <b>Detail Barang</b>\n"
            . "├ Nama: <code>{$peminjaman->barang->nama_barang}</code>\n"
            . "├ Kode: <code>{$peminjaman->barang->kode_barang}</code>\n"
            . "└ Jumlah: {$peminjaman->jumlah} unit\n\n"
            . "👤 <b>Peminjam</b>\n"
            . "└ Nama: {$peminjaman->nama_peminjam}\n\n"
            . "📅 <b>Informasi</b>\n"
            . "├ Tgl Pinjam: {$peminjaman->tanggal_pinjam->format('d/m/Y')}\n"
            . "├ Tgl Kembali: {$peminjaman->tanggal_dikembalikan->format('d/m/Y')}\n"
            . "└ Durasi: {$durasi} hari\n\n"
            . "🕐 <i>Dicatat: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";

        $this->kirimNotifikasi('pengembalian', $pesan);
    }

    public function notifBarangRusak($barangRusak): void
    {
        $waktu = now()->format('d/m/Y H:i');
        $lokasi = $barangRusak->lokasi === 'dalam_ruangan'
            ? "📍 Ruangan: " . ($barangRusak->ruangan->nama_ruangan ?? 'Tidak ditentukan')
            : "📍 Lokasi: Luar Ruangan";

        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "⚠️ <b>LAPORAN BARANG RUSAK</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🏷 <b>Detail Barang</b>\n"
            . "├ Nama: <code>{$barangRusak->barang->nama_barang}</code>\n"
            . "├ Kode: <code>{$barangRusak->barang->kode_barang}</code>\n"
            . "├ Jumlah Rusak: {$barangRusak->jumlah} unit\n"
            . "└ {$lokasi}\n\n"
            . "🔧 <b>Kerusakan</b>\n"
            . "├ Jenis: {$barangRusak->jenis_kerusakan}\n"
            . "├ Tanggal: {$barangRusak->tanggal_rusak->format('d/m/Y')}\n"
            . "└ Deskripsi: " . ($barangRusak->deskripsi_kerusakan ?: '-') . "\n\n"
            . "🕐 <i>Dilaporkan: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";

        $this->kirimNotifikasi('barang_rusak', $pesan);
    }

    public function notifBarangMasuk($barangMasuk): void
    {
        $waktu = now()->format('d/m/Y H:i');
        $harga = $barangMasuk->harga ? 'Rp ' . number_format($barangMasuk->harga, 0, ',', '.') : '-';

        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "📥 <b>BARANG MASUK</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🏷 <b>Detail Barang</b>\n"
            . "├ Nama: <code>{$barangMasuk->barang->nama_barang}</code>\n"
            . "├ Kode: <code>{$barangMasuk->barang->kode_barang}</code>\n"
            . "└ Jumlah: +{$barangMasuk->jumlah} unit\n\n"
            . "📋 <b>Informasi</b>\n"
            . "├ Sumber: {$barangMasuk->sumber_barang}\n"
            . "├ Harga: {$harga}\n"
            . "├ Tanggal: {$barangMasuk->tanggal_masuk->format('d/m/Y')}\n"
            . "└ Catatan: " . ($barangMasuk->catatan ?: '-') . "\n\n"
            . "🕐 <i>Dicatat: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";

        $this->kirimNotifikasi('barang_masuk', $pesan);
    }

    public function notifBarangKeluar($barangKeluar): void
    {
        $waktu = now()->format('d/m/Y H:i');

        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "📤 <b>BARANG KELUAR</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🏷 <b>Detail Barang</b>\n"
            . "├ Nama: <code>{$barangKeluar->barang->nama_barang}</code>\n"
            . "├ Kode: <code>{$barangKeluar->barang->kode_barang}</code>\n"
            . "└ Jumlah: -{$barangKeluar->jumlah} unit\n\n"
            . "📋 <b>Informasi</b>\n"
            . "├ Alasan: {$barangKeluar->alasan_keluar}\n"
            . "├ Penerima: " . ($barangKeluar->penerima ?: '-') . "\n"
            . "├ Tanggal: {$barangKeluar->tanggal_keluar->format('d/m/Y')}\n"
            . "└ Catatan: " . ($barangKeluar->catatan ?: '-') . "\n\n"
            . "🕐 <i>Dicatat: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";

        $this->kirimNotifikasi('barang_keluar', $pesan);
    }

    public function notifUpdateStatusBarangRusak($barangRusak, string $oldStatus): void
    {
        $waktu = now()->format('d/m/Y H:i');

        $statusLabels = [
            'dilaporkan' => '📋 Dilaporkan',
            'diproses' => '🔧 Sedang Diproses',
            'diperbaiki' => '✅ Sudah Diperbaiki',
            'tidak_bisa_diperbaiki' => '❌ Tidak Bisa Diperbaiki',
        ];

        $oldStatusLabel = $statusLabels[$oldStatus] ?? $oldStatus;
        $newStatusLabel = $statusLabels[$barangRusak->status] ?? $barangRusak->status;

        $emoji = match ($barangRusak->status) {
            'diproses' => '🔧',
            'diperbaiki' => '✅',
            'tidak_bisa_diperbaiki' => '❌',
            default => '📋',
        };

        // Hitung sisa barang yang masih rusak (belum diperbaiki)
        $sisaRusak = $barangRusak->barang->barangRusak()
            ->whereIn('status', ['dilaporkan', 'diproses'])
            ->sum('jumlah');

        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "{$emoji} <b>UPDATE STATUS PERBAIKAN</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "🏷 <b>Detail Barang</b>\n"
            . "├ Nama: <code>{$barangRusak->barang->nama_barang}</code>\n"
            . "├ Kode: <code>{$barangRusak->barang->kode_barang}</code>\n"
            . "├ Jumlah Diupdate: {$barangRusak->jumlah} unit\n"
            . "└ Kerusakan: {$barangRusak->jenis_kerusakan}\n\n"
            . "📊 <b>Perubahan Status</b>\n"
            . "├ Sebelum: {$oldStatusLabel}\n"
            . "├ Sesudah: {$newStatusLabel}\n"
            . "└ Sisa Rusak: {$sisaRusak} unit\n\n"
            . "📝 <b>Catatan</b>\n"
            . "└ " . ($barangRusak->catatan_status ?: '-') . "\n\n"
            . "🕐 <i>Diupdate: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";

        $this->kirimNotifikasi('barang_rusak', $pesan);
    }
}
