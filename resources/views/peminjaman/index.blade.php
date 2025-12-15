@extends('layouts.app')
@section('title', 'Peminjaman')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Peminjaman Barang</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('peminjaman.scan') }}" class="btn btn-info"><i class="bi bi-qr-code-scan me-1"></i>Scan QR</a>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
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
                        <th>Peminjam</th>
                        <th>Status</th>
                        <th>Dikembalikan</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $item)
                    <tr>
                        <td data-order="{{ $item->tanggal_pinjam->format('Y-m-d') }}">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->nama_peminjam }}</td>
                        <td><span class="badge bg-{{ $item->status == 'dipinjam' ? 'warning' : 'success' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>{{ $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if($item->status == 'dipinjam')
                            <form action="{{ route('peminjaman.kembalikan', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan barang?')">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Kembalikan"><i class="bi bi-check-lg"></i></button>
                            </form>
                            @endif

                            {{-- Tombol Delete --}}
                    <form action="{{ route('peminjaman.destroy', $item) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Yakin hapus data peminjaman ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
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
