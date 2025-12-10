@extends('layouts.app')
@section('title', 'Scan Barang Masuk')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Scan QR Barang Masuk</h4>
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="row">
    <div class="col-lg-5 mb-3 mb-lg-0">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-qr-code-scan me-2"></i>Scanner</span>
                <span class="badge bg-success" id="scannerStatus">Aktif</span>
            </div>
            <div class="card-body">
                <div id="reader" class="mb-3"></div>
                <button class="btn btn-outline-primary w-100 mb-3 d-none" id="btnScanUlang" onclick="scanUlang()">
                    <i class="bi bi-arrow-repeat me-1"></i>Scan Ulang
                </button>
                <div class="input-group">
                    <input type="text" id="kodeManual" class="form-control" placeholder="Kode Barang">
                    <button class="btn btn-primary" onclick="cariBarang()">Cari</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-arrow-in-down me-2"></i>Form Barang Masuk</div>
            <div class="card-body" id="formArea">
                <div class="text-center text-muted py-4">
                    <i class="bi bi-qr-code display-1 mb-3 d-block opacity-25"></i>
                    <p>Scan QR Code atau masukkan kode barang</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode;
let scannerRunning = false;

function initScanner() {
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, 
        { fps: 10, qrbox: 250 }, 
        onScanSuccess
    ).then(() => {
        scannerRunning = true;
        updateScannerStatus(true);
    }).catch(err => {
        document.getElementById('reader').innerHTML = '<div class="alert alert-warning mb-0">Kamera tidak tersedia. Gunakan input manual.</div>';
        updateScannerStatus(false);
    });
}

function updateScannerStatus(active) {
    const status = document.getElementById('scannerStatus');
    const btnScanUlang = document.getElementById('btnScanUlang');
    if (active) {
        status.className = 'badge bg-success';
        status.textContent = 'Aktif';
        btnScanUlang.classList.add('d-none');
    } else {
        status.className = 'badge bg-secondary';
        status.textContent = 'Berhenti';
        btnScanUlang.classList.remove('d-none');
    }
}

function stopScanner() {
    if (html5QrCode && scannerRunning) {
        html5QrCode.stop().then(() => {
            scannerRunning = false;
            updateScannerStatus(false);
        }).catch(err => console.log(err));
    }
}

function scanUlang() {
    document.getElementById('formArea').innerHTML = `
        <div class="text-center text-muted py-4">
            <i class="bi bi-qr-code display-1 mb-3 d-block opacity-25"></i>
            <p>Scan QR Code atau masukkan kode barang</p>
        </div>
    `;
    if (!scannerRunning) {
        initScanner();
    }
}

function onScanSuccess(decodedText) {
    stopScanner();
    let kode = decodedText;
    if (decodedText.includes('/barang/')) kode = decodedText.split('/barang/')[1];
    fetchBarang(kode);
}

function cariBarang() {
    const kode = document.getElementById('kodeManual').value;
    if (kode) {
        stopScanner();
        fetchBarang(kode);
    }
}

function fetchBarang(kode) {
    document.getElementById('formArea').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Mencari barang...</p></div>';
    
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
                    <i class="bi bi-check-circle me-2"></i><strong>${b.kode_barang}</strong> - ${b.nama_barang}<br>
                    <small class="text-muted">Stok saat ini: ${b.jumlah} ${b.satuan}</small>
                </div>
                <form id="formMasuk">
                    <input type="hidden" name="barang_id" value="${b.id}">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" min="1" required autofocus>
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
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="scanUlang()"><i class="bi bi-arrow-repeat me-1"></i>Scan Lain</button>
                    </div>
                </form>
            `;
            document.getElementById('formMasuk').addEventListener('submit', submitForm);
        } else {
            document.getElementById('formArea').innerHTML = `
                <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>${data.message}</div>
                <button class="btn btn-outline-primary" onclick="scanUlang()"><i class="bi bi-arrow-repeat me-1"></i>Coba Lagi</button>
            `;
        }
    });
}

function submitForm(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
    
    const formData = new FormData(e.target);
    fetch('{{ route("barang-masuk.scan.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('formArea').innerHTML = `
                <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Barang masuk berhasil disimpan!</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" onclick="scanUlang()"><i class="bi bi-qr-code-scan me-1"></i>Scan Barang Lain</button>
                    <a href="{{ route('barang-masuk.index') }}" class="btn btn-outline-secondary">Kembali ke Daftar</a>
                </div>
            `;
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan';
            alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
        }
    });
}

// Initialize scanner on page load
initScanner();
</script>
@endpush
