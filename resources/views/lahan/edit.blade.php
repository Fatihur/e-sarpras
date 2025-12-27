@extends('layouts.app')
@section('title', 'Edit Lahan')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit Lahan</h4>
        <a href="{{ route('lahan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('lahan.update', $lahan) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Kode Lahan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_lahan" class="form-control @error('kode_lahan') is-invalid @enderror"
                        value="{{ old('kode_lahan', $lahan->kode_lahan) }}" required>
                    @error('kode_lahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lahan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lahan" class="form-control"
                        value="{{ old('nama_lahan', $lahan->nama_lahan) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi_lahan" class="form-control"
                        value="{{ old('lokasi_lahan', $lahan->lokasi_lahan) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Luas Bangunan (m²)</label>
                    <input type="number" name="luas_bangunan" class="form-control"
                        value="{{ old('luas_bangunan', $lahan->luas_bangunan) }}" step="0.01" min="0"
                        placeholder="Contoh: 500.00">
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
            </form>
        </div>
    </div>
@endsection