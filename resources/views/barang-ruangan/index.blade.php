@extends('layouts.app')
@section('title', 'Barang Ruangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Barang per Ruangan</h4>

    @if(auth()->user()->isAdminOrManajemen())
        <a href="{{ route('barang-ruangan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah
        </a>
    @endif
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <select name="ruangan_id" class="form-select">
                    <option value="">Semua Ruangan</option>
                    @foreach($semuaRuangan as $r)
                        <option value="{{ $r->id }}"
                            {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($ruangan as $r)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-door-open me-2"></i>{{ $r->nama_ruangan }}
                    </span>
                    <span class="badge bg-primary">
                        {{ $r->barang->count() }} item
                    </span>
                </div>

                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($r->barang->take(5) as $b)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $b->nama_barang }}</span>
                                <span class="badge bg-secondary">
                                    {{ $b->pivot->jumlah }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">
                                Tidak ada barang
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div class="card-footer">
                    <a href="{{ route('barang-ruangan.show', $r) }}"
                       class="btn btn-sm btn-outline-primary">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Tidak ada data ruangan</div>
        </div>
    @endforelse
</div>

{{ $ruangan->links() }}
@endsection
