<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\BarangRuangan;
use App\Models\BarangRusak;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'totalBarang' => Barang::count(),
            'totalBarangRusak' => BarangRusak::count(),
            'totalBarangMasuk' => BarangMasuk::count(),
            'totalBarangKeluar' => BarangKeluar::count(),
            'totalPeminjamanAktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'totalPeminjamanSelesai' => Peminjaman::where('status', 'dikembalikan')->count(),
            'totalBarangRuangan' => BarangRuangan::sum('jumlah'),
        ];

        // Grafik barang masuk/keluar 6 bulan terakhir
        $data['grafikMasuk'] = BarangMasuk::select(
            DB::raw('MONTH(tanggal_masuk) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $data['grafikKeluar'] = BarangKeluar::select(
            DB::raw('MONTH(tanggal_keluar) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
            ->whereYear('tanggal_keluar', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Barang rusak terbaru
        $data['barangRusakTerbaru'] = BarangRusak::with(['barang', 'ruangan'])
            ->orderBy('tanggal_rusak', 'desc')
            ->limit(5)
            ->get();

        // Peminjaman aktif terbaru
        $data['peminjamanAktif'] = Peminjaman::with('barang')
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('data', 'user'));
    }
}
