<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['barang', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create(Request $request)
    {
        // Tampilkan barang yang memiliki stok aktif berdasarkan breakdown dinamis
        $barang = Barang::where('jumlah', '>', 0)->get()->filter(function ($item) {
            return $item->status_breakdown['aktif'] > 0;
        });

        // Jika ada barang_id dari scan, pre-select barang tersebut
        $selectedBarang = null;
        if ($request->filled('barang_id')) {
            $selectedBarang = Barang::find($request->barang_id);
        }

        return view('peminjaman.create', compact('barang', 'selectedBarang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'nama_peminjam' => 'required|string|max:255',
            'kontak_peminjam' => 'nullable|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'dipinjam';

        $peminjaman = Peminjaman::create($validated);

        // Update status barang
        Barang::find($validated['barang_id'])->update(['status_barang' => 'dipinjam']);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifPeminjaman($peminjaman->load('barang'));

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => now(),
        ]);

        // Update status barang
        $peminjaman->barang->update(['status_barang' => 'aktif']);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifPengembalian($peminjaman->load('barang'));

        return back()->with('success', 'Barang berhasil dikembalikan.');
    }
    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            return back()->with('error', 'Barang masih dipinjam, tidak bisa dihapus.');
        }

        if ($peminjaman->barang) {
            $peminjaman->barang->update([
                'status_barang' => 'aktif'
            ]);
        }

        $peminjaman->delete();

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function scan()
    {
        return view('peminjaman.scan');
    }

    public function scanProcess(Request $request)
    {
        $barang = Barang::where('kode_barang', $request->kode)->first();

        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan.']);
        }

        // Ambil breakdown dinamis
        $breakdown = $barang->status_breakdown;

        return response()->json([
            'success' => true,
            'barang' => $barang,
            'breakdown' => $breakdown,
            'tersedia' => $breakdown['aktif'] > 0,
        ]);
    }
}
