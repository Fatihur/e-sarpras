@extends('layouts.app')
@section('title', 'Detail Barang Ruangan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Barang di {{ $ruangan->nama_ruangan }}</h4>
    <a href="{{ route('barang-ruangan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Kode:</strong> {{ $ruangan->kode_ruangan }}</div>
            <div class="col-md-4"><strong>Gedung:</strong> {{ $ruangan->gedung->nama_gedung ?? '-' }}</div>
            <div class="col-md-4"><strong>Penanggung Jawab:</strong> {{ $ruangan->penanggung_jawab ?? '-' }}</div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Daftar Barang</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Kode</th><th>Nama Barang</th><th>Jumlah</th><th>Keterangan</th>@if(auth()->user()->isAdminOrManajemen())
<th width="80">Aksi</th>@endif</tr></thead>
            <tbody>
                @forelse($ruangan->barang as $b)
                <tr>
                    <td><code>{{ $b->kode_barang }}</code></td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->pivot->jumlah }}</td>
                    <td>{{ $b->pivot->keterangan ?? '-' }}</td>
                    @if(auth()->user()->isAdminOrManajemen())
                    <td>
                        @if($b->pivot->id)
                        <form action="{{ route('barang-ruangan.destroy', $b->pivot->id) }}" method="POST" onsubmit="return confirm('Hapus dari ruangan?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada barang di ruangan ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
