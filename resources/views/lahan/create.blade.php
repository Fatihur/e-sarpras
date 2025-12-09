@extends('layouts.app')
@section('title', 'Tambah Lahan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tambah Lahan</h4>
    <a href="{{ route('lahan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('lahan.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Kode Lahan</label>
                <input type="text" class="form-control bg-light" value="Auto Generate" readonly disabled>
                <small class="text-muted">Kode akan di-generate otomatis</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Lahan <span class="text-danger">*</span></label>
                <input type="text" name="nama_lahan" class="form-control" value="{{ old('nama_lahan') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi_lahan" class="form-control" value="{{ old('lokasi_lahan') }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        </form>
    </div>
</div>
@endsection
