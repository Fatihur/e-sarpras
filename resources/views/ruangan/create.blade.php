@extends('layouts.app')
@section('title', 'Tambah Ruangan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tambah Ruangan</h4>
    <a href="{{ route('ruangan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('ruangan.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Ruangan</label>
                    <input type="text" class="form-control bg-light" value="Auto Generate" readonly disabled>
                    <small class="text-muted">Kode akan di-generate otomatis</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_ruangan" class="form-control" value="{{ old('nama_ruangan') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gedung</label>
                    <select name="gedung_id" class="form-select">
                        <option value="">Pilih Gedung</option>
                        @foreach($gedung as $g)
                        <option value="{{ $g->id }}" {{ old('gedung_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gedung }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" value="{{ old('penanggung_jawab') }}">
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
