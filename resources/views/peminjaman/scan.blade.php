@extends('layouts.app')
@section('title', 'Scan Peminjaman')
@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <h4 class="mb-0">Scan QR Peminjaman</h4>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-3 mb-lg-0">
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
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Hasil Scan</div>
                <div class="card-body" id="hasilScan">
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
            document.getElementById('hasilScan').innerHTML = `
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
            document.getElementById('hasilScan').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Mencari barang...</p></div>';

            fetch('{{ route("peminjaman.scan.process") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ kode: kode })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const b = data.barang;
                        const bd = data.breakdown;
                        const tersedia = data.tersedia;

                        // Build breakdown badges
                        let breakdownHtml = '<div class="d-flex flex-wrap gap-1">';
                        if (bd.aktif > 0) breakdownHtml += `<span class="badge bg-success">${bd.aktif} Aktif</span>`;
                        if (bd.dipinjam > 0) breakdownHtml += `<span class="badge bg-warning text-dark">${bd.dipinjam} Dipinjam</span>`;
                        if (bd.rusak > 0) breakdownHtml += `<span class="badge bg-danger">${bd.rusak} Rusak</span>`;
                        if (bd.tidak_bisa_diperbaiki > 0) breakdownHtml += `<span class="badge bg-dark">${bd.tidak_bisa_diperbaiki} Disposed</span>`;
                        breakdownHtml += '</div>';

                        document.getElementById('hasilScan').innerHTML = `
                    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>Barang ditemukan!</div>
                    <table class="table table-sm mb-3">
                        <tr><th width="100">Kode</th><td><code>${b.kode_barang}</code></td></tr>
                        <tr><th>Nama</th><td>${b.nama_barang}</td></tr>
                        <tr><th>Stok Total</th><td>${b.jumlah} unit</td></tr>
                        <tr><th>Status</th><td>${breakdownHtml}</td></tr>
                    </table>
                    <div class="d-flex flex-wrap gap-2">
                        ${tersedia
                                ? `<a href="{{ route('peminjaman.create') }}?barang_id=${b.id}" class="btn btn-primary"><i class="bi bi-arrow-left-right me-1"></i>Pinjam Barang Ini (${bd.aktif} tersedia)</a>`
                                : '<div class="alert alert-warning mb-0 flex-grow-1"><i class="bi bi-exclamation-triangle me-1"></i>Tidak ada stok tersedia untuk dipinjam</div>'}
                        <button class="btn btn-outline-secondary" onclick="scanUlang()"><i class="bi bi-arrow-repeat me-1"></i>Scan Lain</button>
                    </div>
                `;
                    } else {
                        document.getElementById('hasilScan').innerHTML = `
                    <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>${data.message}</div>
                    <button class="btn btn-outline-primary" onclick="scanUlang()"><i class="bi bi-arrow-repeat me-1"></i>Coba Lagi</button>
                `;
                    }
                });
        }

        // Initialize scanner on page load
        initScanner();
    </script>
@endpush