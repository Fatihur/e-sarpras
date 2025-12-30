<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::with(['barang', 'user']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_keluar', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_keluar', '<=', $request->tanggal_sampai);
        }

        $barangKeluar = $query->orderBy('tanggal_keluar', 'desc')->get();
        return view('barang-keluar.index', compact('barangKeluar'));
    }

    public function create()
    {
        $barang = Barang::where('jumlah', '>', 0)->get();
        return view('barang-keluar.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'alasan_keluar' => 'required|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $barang = Barang::find($validated['barang_id']);

        if ($barang->jumlah < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia.']);
        }

        $validated['user_id'] = Auth::id();

        $barangKeluar = BarangKeluar::create($validated);

        // Update jumlah dan status barang
        $barang->decrement('jumlah', $validated['jumlah']);
        if ($barang->jumlah <= 0) {
            $barang->update(['status_barang' => 'keluar']);
        }

        // Kirim notifikasi Telegram
        (new TelegramService())->notifBarangKeluar($barangKeluar->load('barang'));

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        $barangKeluar->barang->increment('jumlah', $barangKeluar->jumlah);
        if ($barangKeluar->barang->status_barang === 'keluar') {
            $barangKeluar->barang->update(['status_barang' => 'aktif']);
        }
        $barangKeluar->delete();
        return redirect()->route('barang-keluar.index')->with('success', 'Data barang keluar berhasil dihapus.');
    }
}
