@extends('layouts.app')
@section('title', 'Data Lahan')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Data Lahan</h4>
    <a href="{{ route('lahan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Lahan</th>
                        <th>Lokasi</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lahan as $item)
                    <tr>
                        <td><code>{{ $item->kode_lahan }}</code></td>
                        <td>{{ $item->nama_lahan }}</td>
                        <td>{{ $item->lokasi_lahan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('lahan.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('lahan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
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
        columnDefs: [
            { orderable: false, targets: [3] }
        ]
    });
});
</script>
@endpush
