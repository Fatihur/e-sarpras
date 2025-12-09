@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Kategori</h4>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('kategori.update', $kategori) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi_kategori" class="form-control" rows="3">{{ old('deskripsi_kategori', $kategori->deskripsi_kategori) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
        </form>
    </div>
</div>
@endsection
