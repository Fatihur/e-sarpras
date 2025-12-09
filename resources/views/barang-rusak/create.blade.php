@extends('layouts.app')
@section('title', 'Tambah Barang Rusak')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Lapor Barang Rusak</h4>
    <a href="{{ route('barang-rusak.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('barang-rusak.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Barang <span class="text-danger">*</span></label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lokasi Kerusakan <span class="text-danger">*</span></label>
                    <select name="lokasi" class="form-select" id="lokasiSelect" required onchange="toggleRuangan()">
                        <option value="dalam_ruangan" {{ old('lokasi') == 'dalam_ruangan' ? 'selected' : '' }}>Dalam Ruangan</option>
                        <option value="luar_ruangan" {{ old('lokasi') == 'luar_ruangan' ? 'selected' : '' }}>Luar Ruangan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3" id="ruanganField">
                    <label class="form-label">Ruangan</label>
                    <select name="ruangan_id" class="form-select">
                        <option value="">Pilih Ruangan</option>
                        @foreach($ruangan as $r)
                        <option value="{{ $r->id }}" {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Rusak <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_rusak" class="form-control" value="{{ old('tanggal_rusak', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kerusakan <span class="text-danger">*</span></label>
                    <input type="text" name="jenis_kerusakan" class="form-control" value="{{ old('jenis_kerusakan') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Bukti</label>
                    <input type="file" name="foto_bukti" class="form-control" accept="image/*">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" class="form-control" rows="3">{{ old('deskripsi_kerusakan') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function toggleRuangan() {
    const lokasi = document.getElementById('lokasiSelect').value;
    document.getElementById('ruanganField').style.display = lokasi === 'dalam_ruangan' ? 'block' : 'none';
}
toggleRuangan();
</script>
@endpush
