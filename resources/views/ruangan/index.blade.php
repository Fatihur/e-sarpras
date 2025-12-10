@extends('layouts.app')
@section('title', 'Data Ruangan')
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h4 class="mb-0">Data Ruangan</h4>
    <a href="{{ route('ruangan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Ruangan</th>
                        <th>Gedung</th>
                        <th>Penanggung Jawab</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ruangan as $item)
                    <tr>
                        <td><code>{{ $item->kode_ruangan }}</code></td>
                        <td>{{ $item->nama_ruangan }}</td>
                        <td>{{ $item->gedung->nama_gedung ?? '-' }}</td>
                        <td>{{ $item->penanggung_jawab ?? '-' }}</td>
                        <td>
                            <a href="{{ route('ruangan.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('ruangan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
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
            { orderable: false, targets: [4] }
        ]
    });
});
</script>
@endpush
