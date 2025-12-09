@extends('layouts.app')
@section('title', 'Scan Peminjaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Scan QR Peminjaman</h4>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Scanner</div>
            <div class="card-body">
                <div id="reader" class="mb-3"></div>
                <div class="text-center">
                    <p class="text-muted">atau masukkan kode manual:</p>
                    <div class="input-group">
                        <input type="text" id="kodeManual" class="form-control" placeholder="Kode Barang">
                        <button class="btn btn-primary" onclick="cariBarang()">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Hasil Scan</div>
            <div class="card-body" id="hasilScan">
                <p class="text-muted text-center">Scan QR Code atau masukkan kode barang</p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const html5QrCode = new Html5Qrcode("reader");
html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess).catch(err => console.log(err));

function onScanSuccess(decodedText) {
    html5QrCode.pause();
    let kode = decodedText;
    if (decodedText.includes('/barang/')) {
        kode = decodedText.split('/barang/')[1];
    }
    fetchBarang(kode);
}

function cariBarang() {
    const kode = document.getElementById('kodeManual').value;
    if (kode) fetchBarang(kode);
}

function fetchBarang(kode) {
    fetch('{{ route("peminjaman.scan.process") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ kode: kode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const b = data.barang;
            document.getElementById('hasilScan').innerHTML = `
                <div class="alert alert-success">Barang ditemukan!</div>
                <p><strong>Kode:</strong> ${b.kode_barang}</p>
                <p><strong>Nama:</strong> ${b.nama_barang}</p>
                <p><strong>Status:</strong> <span class="badge bg-${b.status_barang == 'aktif' ? 'success' : 'warning'}">${b.status_barang}</span></p>
                ${b.status_barang == 'aktif' ? `<a href="{{ route('peminjaman.create') }}?barang_id=${b.id}" class="btn btn-primary">Pinjam Barang Ini</a>` : '<p class="text-warning">Barang tidak tersedia untuk dipinjam</p>'}
            `;
        } else {
            document.getElementById('hasilScan').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
        setTimeout(() => html5QrCode.resume(), 2000);
    });
}
</script>
@endpush
