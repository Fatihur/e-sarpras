@extends('layouts.app')
@section('title', 'Barang Rusak')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Barang Rusak</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('barang-rusak.scan') }}" class="btn btn-info"><i class="bi bi-qr-code-scan me-1"></i>Scan QR</a>
        <a href="{{ route('barang-rusak.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Kerusakan</th>
                        <th>Lokasi</th>
                        <th>Ruangan</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangRusak as $item)
                    <tr>
                        <td data-order="{{ $item->tanggal_rusak->format('Y-m-d') }}">{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jenis_kerusakan }}</td>
                        <td><span class="badge bg-{{ $item->lokasi == 'dalam_ruangan' ? 'info' : 'secondary' }}">{{ $item->lokasi == 'dalam_ruangan' ? 'Dalam Ruangan' : 'Luar Ruangan' }}</span></td>
                        <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>
                            <form action="{{ route('barang-rusak.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
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
            { orderable: false, targets: [5] }
        ]
    });
});
</script>
@endpush
