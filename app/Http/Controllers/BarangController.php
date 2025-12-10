<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'barangRusak', 'peminjaman']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                    ->orWhere('kode_barang', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status_barang', $request->status);
        }

        $barang = $query->orderBy('id', 'desc')->paginate(10);
        $kategori = Kategori::all();

        return view('barang.index', compact('barang', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $kodeBarang = Barang::generateKode();
        return view('barang.create', compact('kategori', 'kodeBarang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:0',
            'nilai_aset' => 'nullable|numeric|min:0',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_barang')) {
            $validated['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }

        // Kode barang dan QR Code akan di-generate otomatis di model
        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'ruangan', 'barangMasuk', 'barangKeluar', 'peminjaman', 'barangRusak']);
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:0',
            'nilai_aset' => 'nullable|numeric|min:0',
            'status_barang' => 'required|in:aktif,rusak,hilang,keluar,dipinjam',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_barang) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $validated['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto_barang) {
            Storage::disk('public')->delete($barang->foto_barang);
        }
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function downloadQr(Barang $barang)
    {
        // Generate QR on-the-fly untuk download (return as JSON for client-side PNG conversion)
        $svgData = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($barang->kode_barang);

        return response()->json([
            'svg' => base64_encode($svgData),
            'kode' => $barang->kode_barang,
        ]);
    }
}
