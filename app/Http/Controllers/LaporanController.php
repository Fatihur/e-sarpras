<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\BarangRuangan;
use App\Models\BarangRusak;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function barangMasuk(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'user']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        $data = $query->orderBy('tanggal_masuk', 'desc')->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf.barang-masuk', compact('data'));
            return $pdf->download('laporan-barang-masuk.pdf');
        }

        if ($request->export === 'excel') {
            return Excel::download(new LaporanExport($data, 'barang_masuk'), 'laporan-barang-masuk.xlsx');
        }

        return view('laporan.barang-masuk', compact('data'));
    }

    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with(['barang', 'user']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_keluar', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_keluar', '<=', $request->tanggal_sampai);
        }

        $data = $query->orderBy('tanggal_keluar', 'desc')->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf.barang-keluar', compact('data'));
            return $pdf->download('laporan-barang-keluar.pdf');
        }

        if ($request->export === 'excel') {
            return Excel::download(new LaporanExport($data, 'barang_keluar'), 'laporan-barang-keluar.xlsx');
        }

        return view('laporan.barang-keluar', compact('data'));
    }

    public function peminjaman(Request $request)
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

        $data = $query->orderBy('tanggal_pinjam', 'desc')->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf.peminjaman', compact('data'));
            return $pdf->download('laporan-peminjaman.pdf');
        }

        if ($request->export === 'excel') {
            return Excel::download(new LaporanExport($data, 'peminjaman'), 'laporan-peminjaman.xlsx');
        }

        return view('laporan.peminjaman', compact('data'));
    }

    public function barangRusak(Request $request)
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

        $data = $query->orderBy('tanggal_rusak', 'desc')->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf.barang-rusak', compact('data'));
            return $pdf->download('laporan-barang-rusak.pdf');
        }

        if ($request->export === 'excel') {
            return Excel::download(new LaporanExport($data, 'barang_rusak'), 'laporan-barang-rusak.xlsx');
        }

        return view('laporan.barang-rusak', compact('data'));
    }

    public function barangRuangan(Request $request)
    {
        $query = BarangRuangan::with(['barang', 'ruangan']);

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $data = $query->get();
        $ruangan = Ruangan::all();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf.barang-ruangan', compact('data'));
            return $pdf->download('laporan-barang-ruangan.pdf');
        }

        if ($request->export === 'excel') {
            return Excel::download(new LaporanExport($data, 'barang_ruangan'), 'laporan-barang-ruangan.xlsx');
        }

        return view('laporan.barang-ruangan', compact('data', 'ruangan'));
    }
}
