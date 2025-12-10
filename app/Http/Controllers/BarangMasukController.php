<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'user']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        $barangMasuk = $query->orderBy('tanggal_masuk', 'desc')->get();
        return view('barang-masuk.index', compact('barangMasuk'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('barang-masuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'sumber_barang' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        $barangMasuk = BarangMasuk::create($validated);

        // Update jumlah barang
        $barang = Barang::find($validated['barang_id']);
        $barang->increment('jumlah', $validated['jumlah']);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifBarangMasuk($barangMasuk->load('barang'));

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $barangMasuk->barang->decrement('jumlah', $barangMasuk->jumlah);
        $barangMasuk->delete();
        return redirect()->route('barang-masuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }

    public function scan()
    {
        return view('barang-masuk.scan');
    }

    public function scanStore(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'sumber_barang' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['tanggal_masuk'] = now();
        $validated['user_id'] = Auth::id();

        $barangMasuk = BarangMasuk::create($validated);

        // Update jumlah barang
        $barang = Barang::find($validated['barang_id']);
        $barang->increment('jumlah', $validated['jumlah']);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifBarangMasuk($barangMasuk->load('barang'));

        return response()->json(['success' => true, 'message' => 'Barang masuk berhasil dicatat.']);
    }
}
