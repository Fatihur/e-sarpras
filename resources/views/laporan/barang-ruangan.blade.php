@extends('layouts.app')
@section('title', 'Laporan Barang Ruangan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Laporan Barang Ruangan</h4>
    <a href="{{ route('laporan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="ruangan_id" class="form-select">
                    <option value="">Semua Ruangan</option>
                    @foreach($ruangan as $r)
                    <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger me-1"><i class="bi bi-file-pdf"></i></a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success"><i class="bi bi-file-excel"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Ruangan</th><th>Kode Barang</th><th>Nama Barang</th><th>Jumlah</th></tr></thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                    <td><code>{{ $item->barang->kode_barang ?? '-' }}</code></td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
