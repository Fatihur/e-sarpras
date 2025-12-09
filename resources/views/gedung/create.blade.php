@extends('layouts.app')
@section('title', 'Tambah Gedung')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tambah Gedung</h4>
    <a href="{{ route('gedung.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('gedung.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Gedung <span class="text-danger">*</span></label>
                <input type="text" name="nama_gedung" class="form-control @error('nama_gedung') is-invalid @enderror" value="{{ old('nama_gedung') }}" required>
                @error('nama_gedung')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat_gedung" class="form-control" rows="2">{{ old('alamat_gedung') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lahan</label>
                <select name="lahan_id" class="form-select">
                    <option value="">Pilih Lahan</option>
                    @foreach($lahan as $l)
                    <option value="{{ $l->id }}" {{ old('lahan_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_lahan }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        </form>
    </div>
</div>
@endsection
