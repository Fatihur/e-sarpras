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
use App\Services\TelegramService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Helper untuk mengirim file ke Telegram
     */
    protected function kirimKeTelegram(string $filePath, string $namaLaporan, string $tipeFile): void
    {
        $userName = auth()->user()->nama ?? 'System';
        $caption = $this->telegramService->notifLaporanExport($namaLaporan, $tipeFile, $userName);
        $this->telegramService->kirimLaporan($filePath, $caption, $tipeFile);
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
            $fileName = 'laporan-barang-masuk-' . now()->format('Y-m-d-His') . '.pdf';
            $filePath = storage_path('app/public/' . $fileName);
            $pdf->save($filePath);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Masuk', 'pdf');

            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        if ($request->export === 'excel') {
            $fileName = 'laporan-barang-masuk-' . now()->format('Y-m-d-His') . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);
            $excelContent = Excel::raw(new LaporanExport($data, 'barang_masuk'), \Maatwebsite\Excel\Excel::XLSX);
            file_put_contents($filePath, $excelContent);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Masuk', 'excel');

            return response()->download($filePath)->deleteFileAfterSend(true);
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
            $fileName = 'laporan-barang-keluar-' . now()->format('Y-m-d-His') . '.pdf';
            $filePath = storage_path('app/public/' . $fileName);
            $pdf->save($filePath);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Keluar', 'pdf');

            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        if ($request->export === 'excel') {
            $fileName = 'laporan-barang-keluar-' . now()->format('Y-m-d-His') . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);
            $excelContent = Excel::raw(new LaporanExport($data, 'barang_keluar'), \Maatwebsite\Excel\Excel::XLSX);
            file_put_contents($filePath, $excelContent);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Keluar', 'excel');

            return response()->download($filePath)->deleteFileAfterSend(true);
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
            $fileName = 'laporan-peminjaman-' . now()->format('Y-m-d-His') . '.pdf';
            $filePath = storage_path('app/public/' . $fileName);
            $pdf->save($filePath);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Peminjaman', 'pdf');

            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        if ($request->export === 'excel') {
            $fileName = 'laporan-peminjaman-' . now()->format('Y-m-d-His') . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);
            $excelContent = Excel::raw(new LaporanExport($data, 'peminjaman'), \Maatwebsite\Excel\Excel::XLSX);
            file_put_contents($filePath, $excelContent);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Peminjaman', 'excel');

            return response()->download($filePath)->deleteFileAfterSend(true);
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
            $fileName = 'laporan-barang-rusak-' . now()->format('Y-m-d-His') . '.pdf';
            $filePath = storage_path('app/public/' . $fileName);
            $pdf->save($filePath);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Rusak', 'pdf');

            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        if ($request->export === 'excel') {
            $fileName = 'laporan-barang-rusak-' . now()->format('Y-m-d-His') . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);
            $excelContent = Excel::raw(new LaporanExport($data, 'barang_rusak'), \Maatwebsite\Excel\Excel::XLSX);
            file_put_contents($filePath, $excelContent);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Rusak', 'excel');

            return response()->download($filePath)->deleteFileAfterSend(true);
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
            $fileName = 'laporan-barang-ruangan-' . now()->format('Y-m-d-His') . '.pdf';
            $filePath = storage_path('app/public/' . $fileName);
            $pdf->save($filePath);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Per Ruangan', 'pdf');

            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        if ($request->export === 'excel') {
            $fileName = 'laporan-barang-ruangan-' . now()->format('Y-m-d-His') . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);
            $excelContent = Excel::raw(new LaporanExport($data, 'barang_ruangan'), \Maatwebsite\Excel\Excel::XLSX);
            file_put_contents($filePath, $excelContent);

            // Kirim ke Telegram
            $this->kirimKeTelegram($filePath, 'Laporan Barang Per Ruangan', 'excel');

            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        return view('laporan.barang-ruangan', compact('data', 'ruangan'));
    }
}

