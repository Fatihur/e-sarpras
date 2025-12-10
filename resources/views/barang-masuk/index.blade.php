@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Barang Masuk</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('barang-masuk.scan') }}" class="btn btn-info"><i class="bi bi-qr-code-scan me-1"></i>Scan QR</a>
        <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Sumber</th>
                        <th>Harga</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangMasuk as $item)
                    <tr>
                        <td data-order="{{ $item->tanggal_masuk->format('Y-m-d') }}">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                        <td><code>{{ $item->barang->kode_barang ?? '-' }}</code></td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->sumber_barang ?? '-' }}</td>
                        <td data-order="{{ $item->harga ?? 0 }}">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('barang-masuk.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
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
            { orderable: false, targets: [6] }
        ]
    });
});
</script>
@endpush
