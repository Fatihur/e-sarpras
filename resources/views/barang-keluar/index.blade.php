@extends('layouts.app')
@section('title', 'Barang Keluar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Barang Keluar</h4>
    <a href="{{ route('barang-keluar.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Jumlah</th><th>Alasan</th><th>Penerima</th><th width="80">Aksi</th></tr></thead>
            <tbody>
                @forelse($barangKeluar as $item)
                <tr>
                    <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                    <td><code>{{ $item->barang->kode_barang ?? '-' }}</code></td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->alasan_keluar }}</td>
                    <td>{{ $item->penerima ?? '-' }}</td>
                    <td>
                        <form action="{{ route('barang-keluar.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangKeluar->hasPages())<div class="card-footer">{{ $barangKeluar->links() }}</div>@endif
</div>
@endsection
