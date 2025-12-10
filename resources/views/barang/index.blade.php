@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Data Barang</h4>
    <a href="{{ route('barang.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>QR</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $item)
                    <tr>
                        <td><code>{{ $item->kode_barang }}</code></td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                        <td>
                            @php $statusColors = ['aktif'=>'success','rusak'=>'danger','dipinjam'=>'warning','keluar'=>'secondary','hilang'=>'dark']; @endphp
                            <span class="badge bg-{{ $statusColors[$item->status_barang] ?? 'secondary' }}">
                                @if($item->status_barang == 'rusak')
                                    {{ $item->barangRusak->count() }} Rusak
                                @elseif($item->status_barang == 'dipinjam')
                                    {{ $item->peminjaman->where('status', 'dipinjam')->count() }} Dipinjam
                                @else
                                    {{ ucfirst($item->status_barang) }}
                                @endif
                            </span>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { first: "Pertama", last: "Terakhir", next: "›", previous: "‹" }
        },
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5, 6] }
        ]
    });
});

document.querySelectorAll('.btn-download-qr').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = this.dataset.url;
        fetch(url)
            .then(r => r.json())
            .then(data => {
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
