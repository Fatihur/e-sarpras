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

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->paginate(10);
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $barang = Barang::where('status_barang', 'aktif')->where('jumlah', '>', 0)->get();
        return view('peminjaman.create', compact('barang'));
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

        return response()->json([
            'success' => true,
            'barang' => $barang,
            'status' => $barang->status_barang,
        ]);
    }
}
