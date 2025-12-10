<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class BarangRuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::with(['barang', 'gedung']);

        if ($request->filled('ruangan_id')) {
            $query->where('id', $request->ruangan_id);
        }

        $ruangan = $query->paginate(10);
        $semuaRuangan = Ruangan::all();
        
        return view('barang-ruangan.index', compact('ruangan', 'semuaRuangan'));
    }

    public function show(Ruangan $ruangan)
    {
        $ruangan->load(['barang' => function($query) {
            $query->withPivot('id', 'jumlah', 'keterangan');
        }, 'gedung']);
        return view('barang-ruangan.show', compact('ruangan'));
    }

    public function create()
    {
        $ruangan = Ruangan::all();
        $barang = Barang::where('status_barang', 'aktif')->get();
        return view('barang-ruangan.create', compact('ruangan', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $existing = BarangRuangan::where('ruangan_id', $validated['ruangan_id'])
            ->where('barang_id', $validated['barang_id'])
            ->first();

        if ($existing) {
            $existing->increment('jumlah', $validated['jumlah']);
        } else {
            BarangRuangan::create($validated);
        }

        return redirect()->route('barang-ruangan.index')->with('success', 'Barang berhasil ditambahkan ke ruangan.');
    }

    public function destroy(BarangRuangan $barangRuangan)
    {
        $barangRuangan->delete();
        return back()->with('success', 'Barang berhasil dihapus dari ruangan.');
    }
}
