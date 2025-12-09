@extends('layouts.app')
@section('title', 'Data Lahan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Lahan</h4>
    <a href="{{ route('lahan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Kode</th><th>Nama Lahan</th><th>Lokasi</th><th width="120">Aksi</th></tr></thead>
            <tbody>
                @forelse($lahan as $item)
                <tr>
                    <td><code>{{ $item->kode_lahan }}</code></td>
                    <td>{{ $item->nama_lahan }}</td>
                    <td>{{ $item->lokasi_lahan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('lahan.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('lahan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
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
    @if($lahan->hasPages())<div class="card-footer">{{ $lahan->links() }}</div>@endif
</div>
@endsection
