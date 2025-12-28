@extends('layouts.app')
@section('title', 'Tambah Peminjaman')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Tambah Peminjaman</h4>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Barang <span class="text-danger">*</span></label>
                        @if($selectedBarang)
                            {{-- From scan - show selected barang info and hidden input --}}
                            <input type="hidden" name="barang_id" value="{{ $selectedBarang->id }}">
                            <div class="form-control bg-light">
                                <strong>{{ $selectedBarang->kode_barang }}</strong> - {{ $selectedBarang->nama_barang }}
                                <br><small class="text-success">{{ $selectedBarang->status_breakdown['aktif'] }} unit
                                    tersedia</small>
                            </div>
                        @else
                            <select name="barang_id" class="form-select select2-searchable" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $b)
                                    <option value="{{ $b->id }}" {{ old('barang_id', request('barang_id')) == $b->id ? 'selected' : '' }}>
                                        {{ $b->kode_barang }} - {{ $b->nama_barang }} ({{ $b->status_breakdown['aktif'] }} tersedia)
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" name="nama_peminjam" class="form-control" value="{{ old('nama_peminjam') }}"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kontak Peminjam</label>
                        <input type="text" name="kontak_peminjam" class="form-control" value="{{ old('kontak_peminjam') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control"
                            value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Rencana Kembali</label>
                        <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali') }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </form>
        </div>
    </div>
@endsection