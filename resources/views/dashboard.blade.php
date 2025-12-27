@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="mb-4">Dashboard</h4>

<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-green">
                <i class="bi bi-box"></i>
            </div>
            <div>
                <h3>{{ $data['totalBarang'] }}</h3>
                <p>Total Barang</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-red">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <h3>{{ $data['totalBarangRusak'] }}</h3>
                <p>Barang Rusak</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-teal">
                <i class="bi bi-box-arrow-in-down"></i>
            </div>
            <div>
                <h3>{{ $data['totalBarangMasuk'] }}</h3>
                <p>Barang Masuk</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-yellow">
                <i class="bi bi-box-arrow-up"></i>
            </div>
            <div>
                <h3>{{ $data['totalBarangKeluar'] }}</h3>
                <p>Barang Keluar</p>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-blue">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div>
                <h3>{{ $data['totalPeminjamanAktif'] }}</h3>
                <p>Peminjaman Aktif</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <h3>{{ $data['totalPeminjamanSelesai'] }}</h3>
                <p>Peminjaman Selesai</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-stat">
            <div class="icon-box icon-teal">
                <i class="bi bi-door-open"></i>
            </div>
            <div>
                <h3>{{ $data['totalBarangRuangan'] }}</h3>
                <p>Barang di Ruangan</p>
            </div>
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
