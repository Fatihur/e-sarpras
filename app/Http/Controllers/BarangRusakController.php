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
        // Tampilkan semua barang karena status sekarang dihitung dinamis
        $barang = Barang::where('jumlah', '>', 0)->get();
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
            'jumlah' => 'required|integer|min:1',
            'foto_bukti' => 'nullable|image|max:2048',
            'lokasi' => 'required|in:dalam_ruangan,luar_ruangan',
        ]);

        // Validasi jumlah tidak melebihi stok aktif
        $barang = Barang::find($validated['barang_id']);
        $breakdown = $barang->status_breakdown;
        if ($validated['jumlah'] > $breakdown['aktif']) {
            return back()->withErrors(['jumlah' => 'Jumlah rusak melebihi stok aktif (' . $breakdown['aktif'] . ' unit tersedia).'])->withInput();
        }

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('barang-rusak', 'public');
        }

        $validated['user_id'] = Auth::id();

        $barangRusak = BarangRusak::create($validated);

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
            'jumlah' => 'required|integer|min:1',
            'foto_bukti' => 'nullable|image|max:2048',
            'lokasi' => 'required|in:dalam_ruangan,luar_ruangan',
        ]);

        // Validasi jumlah tidak melebihi stok aktif
        $barang = Barang::find($validated['barang_id']);
        $breakdown = $barang->status_breakdown;
        if ($validated['jumlah'] > $breakdown['aktif']) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah rusak melebihi stok aktif (' . $breakdown['aktif'] . ' unit tersedia).'
            ], 422);
        }

        $validated['tanggal_rusak'] = now();
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('barang-rusak', 'public');
        }

        $barangRusak = BarangRusak::create($validated);

        // Kirim notifikasi Telegram
        (new TelegramService())->notifBarangRusak($barangRusak->load(['barang', 'ruangan']));

        return response()->json(['success' => true, 'message' => 'Barang rusak berhasil dicatat.']);
    }

    public function destroy(BarangRusak $barangRusak)
    {
        if ($barangRusak->foto_bukti) {
            Storage::disk('public')->delete($barangRusak->foto_bukti);
        }
        // Status barang akan otomatis terupdate karena dihitung dinamis
        $barangRusak->delete();
        return redirect()->route('barang-rusak.index')->with('success', 'Data barang rusak berhasil dihapus.');
    }

    public function updateStatus(Request $request, BarangRusak $barangRusak)
    {
        $validated = $request->validate([
            'status' => 'required|in:dilaporkan,diproses,diperbaiki,tidak_bisa_diperbaiki',
            'jumlah_update' => 'required|integer|min:1|max:' . ($barangRusak->jumlah ?? 1),
            'catatan_status' => 'nullable|string|max:500',
        ]);

        $oldStatus = $barangRusak->status ?? 'dilaporkan';
        $jumlahUpdate = $validated['jumlah_update'];
        $jumlahAwal = $barangRusak->jumlah ?? 1;

        // Jika jumlah yang diupdate kurang dari total, split record
        if ($jumlahUpdate < $jumlahAwal) {
            // Buat record baru untuk sisa yang tidak diupdate (tetap status lama)
            BarangRusak::create([
                'barang_id' => $barangRusak->barang_id,
                'ruangan_id' => $barangRusak->ruangan_id,
                'tanggal_rusak' => $barangRusak->tanggal_rusak,
                'jenis_kerusakan' => $barangRusak->jenis_kerusakan,
                'deskripsi_kerusakan' => $barangRusak->deskripsi_kerusakan,
                'jumlah' => $jumlahAwal - $jumlahUpdate, // Sisa yang tidak diupdate
                'foto_bukti' => $barangRusak->foto_bukti,
                'lokasi' => $barangRusak->lokasi,
                'status' => $oldStatus, // Tetap status lama
                'catatan_status' => $barangRusak->catatan_status,
                'tanggal_update_status' => $barangRusak->tanggal_update_status,
                'user_id' => $barangRusak->user_id,
            ]);
        }

        // Update record yang ada dengan status baru dan jumlah yang diupdate
        $barangRusak->update([
            'jumlah' => $jumlahUpdate,
            'status' => $validated['status'],
            'catatan_status' => $validated['catatan_status'],
            'tanggal_update_status' => now(),
        ]);

        // Status barang akan otomatis terupdate karena dihitung dinamis

        // Kirim notifikasi Telegram
        (new TelegramService())->notifUpdateStatusBarangRusak($barangRusak->load(['barang', 'ruangan']), $oldStatus);

        return back()->with('success', 'Status barang rusak berhasil diupdate (' . $jumlahUpdate . ' unit).');
    }
}
