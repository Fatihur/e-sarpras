@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Barang</h4>
    <a href="{{ route('barang.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama/kode barang..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Jumlah</th><th>Status</th><th>QR</th><th width="150">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                    <tr>
                        <td><code>{{ $item->kode_barang }}</code></td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                        <td>
                            @php $statusColors = ['aktif'=>'success','rusak'=>'danger','dipinjam'=>'warning','keluar'=>'secondary','hilang'=>'dark']; @endphp
                            <span class="badge bg-{{ $statusColors[$item->status_barang] ?? 'secondary' }}">{{ ucfirst($item->status_barang) }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-download-qr" data-url="{{ route('barang.download-qr', $item) }}" title="Download QR"><i class="bi bi-qr-code"></i></button>
                        </td>
                        <td>
                            <a href="{{ route('barang.show', $item) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('barang.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('barang.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data barang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($barang->hasPages())
    <div class="card-footer">{{ $barang->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-download-qr').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = this.dataset.url;
        fetch(url)
            .then(r => r.json())
            .then(data => {
                const svgData = atob(data.svg);
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const img = new Image();
                
                img.onload = function() {
                    canvas.width = 300;
                    canvas.height = 300;
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, 300, 300);
                    ctx.drawImage(img, 0, 0, 300, 300);
                    
                    const link = document.createElement('a');
                    link.download = 'QR_' + data.kode + '.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                };
                
                img.src = 'data:image/svg+xml;base64,' + data.svg;
            });
    });
});
</script>
@endpush
