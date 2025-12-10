@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Barang</h4>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('barang.update', $barang) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                    @error('kode_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                    @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $barang->satuan) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $barang->jumlah) }}" min="0" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nilai Aset (Rp)</label>
                    <input type="number" name="nilai_aset" class="form-control" value="{{ old('nilai_aset', $barang->nilai_aset) }}" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status_barang" class="form-select" required>
                        @foreach(['aktif','rusak','hilang','keluar','dipinjam'] as $s)
                        <option value="{{ $s }}" {{ old('status_barang', $barang->status_barang) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Foto Barang</label>
                    @if($barang->foto_barang)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $barang->foto_barang) }}" alt="Foto {{ $barang->nama_barang }}" class="img-thumbnail" style="max-height: 100px;">
                    </div>
                    @endif
                    <input type="file" name="foto_barang" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
        </form>
    </div>
</div>
@endsection
