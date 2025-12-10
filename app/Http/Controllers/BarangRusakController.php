<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangRusak;
use App\Models\Ruangan;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangRusakController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangRusak::with(['barang', 'ruangan', 'user']);

        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_rusak', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_rusak', '<=', $request->tanggal_sampai);
        }

        $barangRusak = $query->orderBy('tanggal_rusak', 'desc')->get();
        return view('barang-rusak.index', compact('barangRusak'));
    }

    public function create()
    {
        $barang = Barang::where('status_barang', '!=', 'rusak')->get();
        $ruangan = Ruangan::all();
        return view('barang-rusak.create', compact('barang', 'ruangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'tanggal_rusak' => 'required|date',
            'jenis_kerusakan' => 'required|string|max:255',
            'deskripsi_kerusakan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:2048',
            'lokasi' => 'required|in:dalam_ruangan,luar_ruangan',
        ]);

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('barang-rusak', 'public');
        }

        $validated['user_id'] = Auth::id();

        $barangRusak = BarangRusak::create($validated);

        // Update status barang
        Barang::find($validated['barang_id'])->update(['status_barang' => 'rusak']);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifBarangRusak($barangRusak->load(['barang', 'ruangan']));

        return redirect()->route('barang-rusak.index')->with('success', 'Barang rusak berhasil dicatat.');
    }

    public function scan()
    {
        $ruangan = Ruangan::all();
        return view('barang-rusak.scan', compact('ruangan'));
    }

    public function scanStore(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'jenis_kerusakan' => 'required|string|max:255',
            'deskripsi_kerusakan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:2048',
            'lokasi' => 'required|in:dalam_ruangan,luar_ruangan',
        ]);

        $validated['tanggal_rusak'] = now();
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('barang-rusak', 'public');
        }

        $barangRusak = BarangRusak::create($validated);

        // Update status barang
        Barang::find($validated['barang_id'])->update(['status_barang' => 'rusak']);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifBarangRusak($barangRusak->load(['barang', 'ruangan']));

        return response()->json(['success' => true, 'message' => 'Barang rusak berhasil dicatat.']);
    }

    public function destroy(BarangRusak $barangRusak)
    {
        if ($barangRusak->foto_bukti) {
            Storage::disk('public')->delete($barangRusak->foto_bukti);
        }
        $barangRusak->barang->update(['status_barang' => 'aktif']);
        $barangRusak->delete();
        return redirect()->route('barang-rusak.index')->with('success', 'Data barang rusak berhasil dihapus.');
    }

    public function updateStatus(Request $request, BarangRusak $barangRusak)
    {
        $validated = $request->validate([
            'status' => 'required|in:dilaporkan,diproses,diperbaiki,tidak_bisa_diperbaiki',
            'catatan_status' => 'nullable|string|max:500',
        ]);

        $oldStatus = $barangRusak->status ?? 'dilaporkan';
        
        $barangRusak->update([
            'status' => $validated['status'],
            'catatan_status' => $validated['catatan_status'],
            'tanggal_update_status' => now(),
        ]);

        // Update status barang jika sudah diperbaiki
        if ($validated['status'] === 'diperbaiki') {
            $barangRusak->barang->update(['status_barang' => 'aktif']);
        }

        // Kirim notifikasi Telegram
        (new TelegramService())->notifUpdateStatusBarangRusak($barangRusak->load(['barang', 'ruangan']), $oldStatus);

        return back()->with('success', 'Status barang rusak berhasil diupdate.');
    }
}
