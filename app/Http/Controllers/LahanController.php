<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;

class LahanController extends Controller
{
    public function index()
    {
        $lahan = Lahan::paginate(10);
        return view('lahan.index', compact('lahan'));
    }

    public function create()
    {
        return view('lahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'lokasi_lahan' => 'nullable|string',
        ]);

        // Kode lahan akan di-generate otomatis di model
        Lahan::create($validated);

        return redirect()->route('lahan.index')->with('success', 'Lahan berhasil ditambahkan.');
    }

    public function edit(Lahan $lahan)
    {
        return view('lahan.edit', compact('lahan'));
    }

    public function update(Request $request, Lahan $lahan)
    {
        $validated = $request->validate([
            'kode_lahan' => 'required|unique:lahan,kode_lahan,' . $lahan->id,
            'nama_lahan' => 'required|string|max:255',
            'lokasi_lahan' => 'nullable|string',
        ]);

        $lahan->update($validated);

        return redirect()->route('lahan.index')->with('success', 'Lahan berhasil diperbarui.');
    }

    public function destroy(Lahan $lahan)
    {
        $lahan->delete();
        return redirect()->route('lahan.index')->with('success', 'Lahan berhasil dihapus.');
    }
}
