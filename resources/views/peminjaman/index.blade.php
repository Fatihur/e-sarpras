@extends('layouts.app')
@section('title', 'Peminjaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Peminjaman Barang</h4>
    <div>
        <a href="{{ route('peminjaman.scan') }}" class="btn btn-info me-2"><i class="bi bi-qr-code-scan me-1"></i>Scan QR</a>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
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
            <thead><tr><th>Tanggal</th><th>Barang</th><th>Peminjam</th><th>Status</th><th>Dikembalikan</th><th width="100">Aksi</th></tr></thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->nama_peminjam }}</td>
                    <td><span class="badge bg-{{ $item->status == 'dipinjam' ? 'warning' : 'success' }}">{{ ucfirst($item->status) }}</span></td>
                    <td>{{ $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @if($item->status == 'dipinjam')
                        <form action="{{ route('peminjaman.kembalikan', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan barang?')">
                            @csrf
                            <button class="btn btn-sm btn-success" title="Kembalikan"><i class="bi bi-check-lg"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())<div class="card-footer">{{ $peminjaman->links() }}</div>@endif
</div>
@endsection
