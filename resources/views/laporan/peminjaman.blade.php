@extends('layouts.app')
@section('title', 'Laporan Peminjaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Laporan Peminjaman</h4>
    <a href="{{ route('laporan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger me-1"><i class="bi bi-file-pdf"></i></a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-excel"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal Pinjam</th><th>Barang</th><th>Peminjam</th><th>Status</th><th>Dikembalikan</th></tr></thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->nama_peminjam }}</td>
                    <td><span class="badge bg-{{ $item->status == 'dipinjam' ? 'warning' : 'success' }}">{{ ucfirst($item->status) }}</span></td>
                    <td>{{ $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
