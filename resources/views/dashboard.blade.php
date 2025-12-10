@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="mb-4">Dashboard</h4>

<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card primary position-relative">
            <i class="bi bi-box stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalBarang']) }}</div>
            <div class="stat-label">Total Barang</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card danger position-relative">
            <i class="bi bi-exclamation-triangle stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalBarangRusak']) }}</div>
            <div class="stat-label">Barang Rusak</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card success position-relative">
            <i class="bi bi-box-arrow-in-down stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalBarangMasuk']) }}</div>
            <div class="stat-label">Barang Masuk</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
        <div class="stat-card warning position-relative">
            <i class="bi bi-box-arrow-up stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalBarangKeluar']) }}</div>
            <div class="stat-label">Barang Keluar</div>
        </div>
    </div>
</div>

<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-6 col-xl-4">
        <div class="stat-card info position-relative">
            <i class="bi bi-arrow-left-right stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalPeminjamanAktif']) }}</div>
            <div class="stat-label">Peminjaman Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-4">
        <div class="stat-card success position-relative">
            <i class="bi bi-check-circle stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalPeminjamanSelesai']) }}</div>
            <div class="stat-label">Peminjaman Selesai</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="stat-card primary position-relative">
            <i class="bi bi-door-open stat-icon"></i>
            <div class="stat-value">{{ number_format($data['totalBarangRuangan']) }}</div>
            <div class="stat-label">Barang di Ruangan</div>
        </div>
    </div>
</div>

@if($user->isAdmin() || $user->isManajemen())
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle text-danger me-2"></i>Barang Rusak Terbaru</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Barang</th><th>Kerusakan</th><th>Tanggal</th></tr></thead>
                        <tbody>
                            @forelse($data['barangRusakTerbaru'] as $item)
                            <tr>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->jenis_kerusakan }}</td>
                                <td>{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-arrow-left-right text-primary me-2"></i>Peminjaman Aktif</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Barang</th><th>Peminjam</th><th>Tanggal</th></tr></thead>
                        <tbody>
                            @forelse($data['peminjamanAktif'] as $item)
                            <tr>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->nama_peminjam }}</td>
                                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($user->isManajemen())
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-4">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a href="{{ route('scan.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-qr-code-scan me-2"></i>Scan QR Code
                    </a>
                    <a href="{{ route('peminjaman.create') }}" class="btn btn-success btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Peminjaman Baru
                    </a>
                    <a href="{{ route('barang-rusak.create') }}" class="btn btn-danger btn-lg">
                        <i class="bi bi-exclamation-triangle me-2"></i>Lapor Barang Rusak
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
