<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Lahan;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $gedung = Gedung::with('lahan')->paginate(10);
        return view('gedung.index', compact('gedung'));
    }

    public function create()
    {
        $lahan = Lahan::all();
        return view('gedung.create', compact('lahan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'alamat_gedung' => 'nullable|string',
            'lahan_id' => 'nullable|exists:lahan,id',
        ]);

        Gedung::create($validated);

        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit(Gedung $gedung)
    {
        $lahan = Lahan::all();
        return view('gedung.edit', compact('gedung', 'lahan'));
    }

    public function update(Request $request, Gedung $gedung)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'alamat_gedung' => 'nullable|string',
            'lahan_id' => 'nullable|exists:lahan,id',
        ]);

        $gedung->update($validated);

        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Gedung $gedung)
    {
        $gedung->delete();
        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil dihapus.');
    }
}
