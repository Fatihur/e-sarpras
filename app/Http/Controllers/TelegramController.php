<?php

namespace App\Http\Controllers;

use App\Models\LogNotifikasi;
use App\Models\PengaturanTelegram;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanTelegram::first() ?? new PengaturanTelegram();
        $logs = LogNotifikasi::orderBy('waktu_kirim', 'desc')->paginate(20);
        return view('telegram.index', compact('pengaturan', 'logs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bot_token' => 'nullable|string|max:255',
            'group_id' => 'nullable|string|max:100',
        ]);

        $data = [
            'bot_token' => $request->input('bot_token'),
            'group_id' => $request->input('group_id'),
            'notif_peminjaman' => $request->has('notif_peminjaman'),
            'notif_pengembalian' => $request->has('notif_pengembalian'),
            'notif_barang_rusak' => $request->has('notif_barang_rusak'),
            'notif_barang_masuk' => $request->has('notif_barang_masuk'),
            'notif_barang_keluar' => $request->has('notif_barang_keluar'),
        ];

        $pengaturan = PengaturanTelegram::first();
        if ($pengaturan) {
            $pengaturan->update($data);
        } else {
            PengaturanTelegram::create($data);
        }

        return back()->with('success', 'Pengaturan Telegram berhasil disimpan.');
    }

    public function testNotifikasi()
    {
        $service = new TelegramService();
        $waktu = now()->format('d/m/Y H:i:s');
        
        $pesan = "━━━━━━━━━━━━━━━━━━━━━\n"
            . "🔔 <b>TEST NOTIFIKASI</b>\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n\n"
            . "✅ Koneksi berhasil!\n\n"
            . "📱 <b>e-Sarpras</b>\n"
            . "Sistem Sarana & Prasarana\n\n"
            . "🕐 <i>Waktu: {$waktu}</i>\n"
            . "━━━━━━━━━━━━━━━━━━━━━";
        
        $result = $service->kirimNotifikasi('test', $pesan);

        if ($result) {
            return back()->with('success', 'Test notifikasi berhasil dikirim.');
        }

        return back()->with('error', 'Gagal mengirim test notifikasi. Periksa konfigurasi.');
    }
}
