<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::with('gedung')->get();
        return view('ruangan.index', compact('ruangan'));
    }

    public function create()
    {
        $gedung = Gedung::all();
        return view('ruangan.create', compact('gedung'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'gedung_id' => 'nullable|exists:gedung,id',
            'penanggung_jawab' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Kode ruangan akan di-generate otomatis di model
        Ruangan::create($validated);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Ruangan $ruangan)
    {
        $gedung = Gedung::all();
        return view('ruangan.edit', compact('ruangan', 'gedung'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|unique:ruangan,kode_ruangan,' . $ruangan->id,
            'nama_ruangan' => 'required|string|max:255',
            'gedung_id' => 'nullable|exists:gedung,id',
            'penanggung_jawab' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $ruangan->update($validated);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
