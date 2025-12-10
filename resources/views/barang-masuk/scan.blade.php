@extends('layouts.app')
@section('title', 'Scan Barang Masuk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Scan QR Barang Masuk</h4>
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Scanner</div>
            <div class="card-body">
                <div id="reader" class="mb-3"></div>
                <div class="input-group">
                    <input type="text" id="kodeManual" class="form-control" placeholder="Kode Barang">
                    <button class="btn btn-primary" onclick="cariBarang()">Cari</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Form Barang Masuk</div>
            <div class="card-body" id="formArea">
                <p class="text-muted text-center">Scan QR Code terlebih dahulu</p>
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
    if (decodedText.includes('/barang/')) kode = decodedText.split('/barang/')[1];
    fetchBarang(kode);
}

function cariBarang() {
    const kode = document.getElementById('kodeManual').value;
    if (kode) fetchBarang(kode);
}

function fetchBarang(kode) {
    fetch('{{ route("scan.process") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ kode: kode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const b = data.barang;
            document.getElementById('formArea').innerHTML = `
                <div class="alert alert-info mb-3">
                    <strong>${b.kode_barang}</strong> - ${b.nama_barang}<br>
                    <small>Stok saat ini: ${b.jumlah} ${b.satuan}</small>
                </div>
                <form id="formMasuk">
                    <input type="hidden" name="barang_id" value="${b.id}">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Masuk *</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sumber Barang</label>
                        <input type="text" name="sumber_barang" class="form-control" placeholder="Contoh: Pembelian, Hibah, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
                </form>
            `;
            document.getElementById('formMasuk').addEventListener('submit', submitForm);
        } else {
            document.getElementById('formArea').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
        setTimeout(() => html5QrCode.resume(), 2000);
    });
}

function submitForm(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch('{{ route("barang-masuk.scan.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Berhasil disimpan!');
            window.location.href = '{{ route("barang-masuk.index") }}';
        }
    });
}
</script>
@endpush
