@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Barang Masuk</h4>
    <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}" placeholder="Dari">
            </div>
            <div class="col-md-4">
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}" placeholder="Sampai">
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
            <thead><tr><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Jumlah</th><th>Sumber</th><th>Harga</th><th width="80">Aksi</th></tr></thead>
            <tbody>
                @forelse($barangMasuk as $item)
                <tr>
                    <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                    <td><code>{{ $item->barang->kode_barang ?? '-' }}</code></td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->sumber_barang ?? '-' }}</td>
                    <td>Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('barang-masuk.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
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
    @if($barangMasuk->hasPages())<div class="card-footer">{{ $barangMasuk->links() }}</div>@endif
</div>
@endsection
