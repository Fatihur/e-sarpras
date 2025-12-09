@extends('layouts.app')
@section('title', 'Barang Rusak')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Barang Rusak</h4>
    <div>
        <a href="{{ route('barang-rusak.scan') }}" class="btn btn-info me-2"><i class="bi bi-qr-code-scan me-1"></i>Scan QR</a>
        <a href="{{ route('barang-rusak.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="lokasi" class="form-select">
                    <option value="">Semua Lokasi</option>
                    <option value="dalam_ruangan" {{ request('lokasi') == 'dalam_ruangan' ? 'selected' : '' }}>Dalam Ruangan</option>
                    <option value="luar_ruangan" {{ request('lokasi') == 'luar_ruangan' ? 'selected' : '' }}>Luar Ruangan</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Barang</th><th>Kerusakan</th><th>Lokasi</th><th>Ruangan</th><th width="80">Aksi</th></tr></thead>
            <tbody>
                @forelse($barangRusak as $item)
                <tr>
                    <td>{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jenis_kerusakan }}</td>
                    <td><span class="badge bg-{{ $item->lokasi == 'dalam_ruangan' ? 'info' : 'secondary' }}">{{ $item->lokasi == 'dalam_ruangan' ? 'Dalam Ruangan' : 'Luar Ruangan' }}</span></td>
                    <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                    <td>
                        <form action="{{ route('barang-rusak.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangRusak->hasPages())<div class="card-footer">{{ $barangRusak->links() }}</div>@endif
</div>
@endsection
