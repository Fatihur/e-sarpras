@extends('layouts.app')
@section('title', 'Data Gedung')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Gedung</h4>
    <a href="{{ route('gedung.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nama Gedung</th><th>Alamat</th><th>Lahan</th><th width="120">Aksi</th></tr></thead>
            <tbody>
                @forelse($gedung as $item)
                <tr>
                    <td>{{ $item->nama_gedung }}</td>
                    <td>{{ $item->alamat_gedung ?? '-' }}</td>
                    <td>{{ $item->lahan->nama_lahan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('gedung.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('gedung.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
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
    @if($gedung->hasPages())<div class="card-footer">{{ $gedung->links() }}</div>@endif
</div>
@endsection
