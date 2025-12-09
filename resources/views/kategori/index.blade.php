@extends('layouts.app')
@section('title', 'Data Kategori')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Kategori</h4>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Barang</th><th width="120">Aksi</th></tr></thead>
            <tbody>
                @forelse($kategori as $item)
                <tr>
                    <td>{{ $item->nama_kategori }}</td>
                    <td>{{ $item->deskripsi_kategori ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $item->barang_count }}</span></td>
                    <td>
                        <a href="{{ route('kategori.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('kategori.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategori->hasPages())<div class="card-footer">{{ $kategori->links() }}</div>@endif
</div>
@endsection
