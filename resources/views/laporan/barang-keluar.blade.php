@extends('layouts.app')
@section('title', 'Laporan Barang Keluar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Laporan Barang Keluar</h4>
    <a href="{{ route('laporan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3">
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
            <thead><tr><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Jumlah</th><th>Alasan</th><th>Penerima</th></tr></thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                    <td><code>{{ $item->barang->kode_barang ?? '-' }}</code></td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->alasan_keluar }}</td>
                    <td>{{ $item->penerima ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
