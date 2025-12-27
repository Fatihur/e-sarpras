@extends('layouts.app')
@section('title', 'Detail Barang')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Barang</h4>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if($barang->foto_barang)
                <div class="card mb-4">
                    <div class="card-header">Foto Barang</div>
                    <div class="card-body text-center">
                        <img src="{{ Storage::url($barang->foto_barang) }}" alt="{{ $barang->nama_barang }}"
                            class="img-fluid rounded" style="max-height: 300px;"
                            onerror="this.parentElement.innerHTML='<p class=\'text-muted\'>Foto tidak ditemukan</p>'">
                    </div>
                </div>
            @endif
            <div class="card mb-4">
                <div class="card-header">Informasi Barang</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Kode Barang</label>
                            <div class="fw-bold"><code>{{ $barang->kode_barang }}</code></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nama Barang</label>
                            <div class="fw-bold">{{ $barang->nama_barang }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Kategori</label>
                            <div>{{ $barang->kategori->nama_kategori ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Jumlah</label>
                            <div>{{ $barang->jumlah }} {{ $barang->satuan }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Status</label>
                            <div><span
                                    class="badge bg-{{ $barang->status_barang == 'aktif' ? 'success' : ($barang->status_barang == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($barang->status_barang) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nilai Aset</label>
                            <div>Rp {{ number_format($barang->nilai_aset, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">QR Code</div>
                <div class="card-body text-center">
                    <img src="{{ $barang->qr_code_image }}" alt="QR Code {{ $barang->kode_barang }}" class="img-fluid mb-3"
                        style="max-width: 200px;">
                    <p class="small text-muted mb-2">{{ $barang->kode_barang }}</p>
                    <button type="button" id="btnDownloadQr" class="btn btn-primary btn-sm"
                        data-url="{{ route('barang.download-qr', $barang) }}"><i class="bi bi-download me-1"></i>Download
                        QR</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('btnDownloadQr').addEventListener('click', function () {
            const url = this.dataset.url;
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const img = new Image();

                    img.onload = function () {
                        // Increased height for text label
                        canvas.width = 300;
                        canvas.height = 380;

                        // White background
                        ctx.fillStyle = '#ffffff';
                        ctx.fillRect(0, 0, 300, 380);

                        // Draw QR code
                        ctx.drawImage(img, 0, 0, 300, 300);

                        // Draw border around QR
                        ctx.strokeStyle = '#e0e0e0';
                        ctx.lineWidth = 1;
                        ctx.strokeRect(0, 0, 300, 300);

                        // Text labels
                        ctx.fillStyle = '#000000';
                        ctx.textAlign = 'center';

                        // Kode barang (bold)
                        ctx.font = 'bold 16px Arial';
                        ctx.fillText(data.kode, 150, 330);

                        // Nama barang
                        ctx.font = '14px Arial';
                        const nama = data.nama.length > 30 ? data.nama.substring(0, 27) + '...' : data.nama;
                        ctx.fillText(nama, 150, 355);

                        // Footer
                        ctx.font = '10px Arial';
                        ctx.fillStyle = '#888888';
                        ctx.fillText('e-Sarpras Inventory System', 150, 375);

                        const link = document.createElement('a');
                        link.download = 'QR_' + data.kode + '.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                    };

                    img.src = 'data:image/svg+xml;base64,' + data.svg;
                });
        });
    </script>
@endpush