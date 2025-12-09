<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function process(Request $request)
    {
        $kode = $request->kode;
        
        // Extract kode dari URL jika ada
        if (str_contains($kode, '/barang/')) {
            preg_match('/\/barang\/(\d+)/', $kode, $matches);
            if (isset($matches[1])) {
                $barang = Barang::with('kategori')->find($matches[1]);
            }
        } else {
            $barang = Barang::with('kategori')
                ->where('kode_barang', $kode)
                ->orWhere('id', $kode)
                ->first();
        }

        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan.']);
        }

        return response()->json([
            'success' => true,
            'barang' => $barang,
        ]);
    }
}
