@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tambah Barang</h4>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Barang</label>
                    <input type="text" class="form-control bg-light" value="{{ $kodeBarang }}" readonly disabled>
                    <small class="text-muted">Kode akan di-generate otomatis</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="satuan" class="form-control" value="{{ old('satuan', 'unit') }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 0) }}" min="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nilai Aset (Rp)</label>
                    <input type="number" name="nilai_aset" class="form-control" value="{{ old('nilai_aset', 0) }}" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Barang</label>
                    <input type="file" name="foto_barang" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>QR Code akan otomatis di-generate setelah barang disimpan.</div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        </form>
    </div>
</div>
@endsection
