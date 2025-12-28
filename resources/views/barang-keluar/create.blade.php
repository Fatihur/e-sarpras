@extends('layouts.app')
@section('title', 'Tambah Barang Keluar')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Tambah Barang Keluar</h4>
        <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary"><i
                class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('barang-keluar.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_keluar" class="form-control"
                            value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Barang <span class="text-danger">*</span></label>
                        <select name="barang_id"
                            class="form-select select2-searchable @error('barang_id') is-invalid @enderror" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->kode_barang }} - {{ $b->nama_barang }} (Stok: {{ $b->jumlah }})</option>
                            @endforeach
                        </select>
                        @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                            value="{{ old('jumlah', 1) }}" min="1" required>
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Alasan Keluar <span class="text-danger">*</span></label>
                        <select name="alasan_keluar" class="form-select" required>
                            <option value="Dijual" {{ old('alasan_keluar') == 'Dijual' ? 'selected' : '' }}>Dijual</option>
                            <option value="Dihibahkan" {{ old('alasan_keluar') == 'Dihibahkan' ? 'selected' : '' }}>Dihibahkan
                            </option>
                            <option value="Dimusnahkan" {{ old('alasan_keluar') == 'Dimusnahkan' ? 'selected' : '' }}>
                                Dimusnahkan</option>
                            <option value="Dipindah" {{ old('alasan_keluar') == 'Dipindah' ? 'selected' : '' }}>Dipindah
                                Permanen</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Penerima</label>
                        <input type="text" name="penerima" class="form-control" value="{{ old('penerima') }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </form>
        </div>
    </div>
@endsection