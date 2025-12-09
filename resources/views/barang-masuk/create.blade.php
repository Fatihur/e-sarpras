@extends('layouts.app')
@section('title', 'Tambah Barang Masuk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tambah Barang Masuk</h4>
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('barang-masuk.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Barang <span class="text-danger">*</span></label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sumber Barang</label>
                    <input type="text" name="sumber_barang" class="form-control" value="{{ old('sumber_barang') }}" placeholder="Vendor/Hibah">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" min="0">
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
