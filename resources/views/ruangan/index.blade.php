@extends('layouts.app')
@section('title', 'Data Ruangan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Ruangan</h4>
    <a href="{{ route('ruangan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Kode</th><th>Nama Ruangan</th><th>Gedung</th><th>Penanggung Jawab</th><th width="120">Aksi</th></tr></thead>
            <tbody>
                @forelse($ruangan as $item)
                <tr>
                    <td><code>{{ $item->kode_ruangan }}</code></td>
                    <td>{{ $item->nama_ruangan }}</td>
                    <td>{{ $item->gedung->nama_gedung ?? '-' }}</td>
                    <td>{{ $item->penanggung_jawab ?? '-' }}</td>
                    <td>
                        <a href="{{ route('ruangan.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('ruangan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ruangan->hasPages())<div class="card-footer">{{ $ruangan->links() }}</div>@endif
</div>
@endsection
