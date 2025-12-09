@extends('layouts.app')
@section('title', 'Edit Ruangan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Ruangan</h4>
    <a href="{{ route('ruangan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('ruangan.update', $ruangan) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_ruangan" class="form-control @error('kode_ruangan') is-invalid @enderror" value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}" required>
                    @error('kode_ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_ruangan" class="form-control" value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gedung</label>
                    <select name="gedung_id" class="form-select">
                        <option value="">Pilih Gedung</option>
                        @foreach($gedung as $g)
                        <option value="{{ $g->id }}" {{ old('gedung_id', $ruangan->gedung_id) == $g->id ? 'selected' : '' }}>{{ $g->nama_gedung }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" value="{{ old('penanggung_jawab', $ruangan->penanggung_jawab) }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $ruangan->keterangan) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
        </form>
    </div>
</div>
@endsection
