@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
    <h4 class="mb-4">Laporan</h4>
    <div class="row">
        {{-- Laporan Keseluruhan - Featured --}}
        <div class="col-12 mb-4">
            <div class="card border-primary">
                <div
                    class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 bg-light">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-collection display-4 text-primary"></i>
                        <div>
                            <h5 class="mb-1">Laporan Keseluruhan</h5>
                            <p class="text-muted small mb-0">Export semua laporan dalam satu file PDF atau Excel</p>
                        </div>
                    </div>
                    <a href="{{ route('laporan.keseluruhan') }}" class="btn btn-primary"><i
                            class="bi bi-file-earmark-spreadsheet me-1"></i>Lihat Laporan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-in-down display-4 text-success mb-3"></i>
                    <h5>Barang Masuk</h5>
                    <p class="text-muted small">Laporan barang yang masuk ke inventaris</p>
                    <a href="{{ route('laporan.barang-masuk') }}" class="btn btn-outline-success">Lihat Laporan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-up display-4 text-warning mb-3"></i>
                    <h5>Barang Keluar</h5>
                    <p class="text-muted small">Laporan barang yang keluar dari inventaris</p>
                    <a href="{{ route('laporan.barang-keluar') }}" class="btn btn-outline-warning">Lihat Laporan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-left-right display-4 text-primary mb-3"></i>
                    <h5>Peminjaman</h5>
                    <p class="text-muted small">Laporan peminjaman dan pengembalian barang</p>
                    <a href="{{ route('laporan.peminjaman') }}" class="btn btn-outline-primary">Lihat Laporan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                    <h5>Barang Rusak</h5>
                    <p class="text-muted small">Laporan barang yang mengalami kerusakan</p>
                    <a href="{{ route('laporan.barang-rusak') }}" class="btn btn-outline-danger">Lihat Laporan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-door-open display-4 text-info mb-3"></i>
                    <h5>Barang Ruangan</h5>
                    <p class="text-muted small">Laporan barang per ruangan</p>
                    <a href="{{ route('laporan.barang-ruangan') }}" class="btn btn-outline-info">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
@endsection