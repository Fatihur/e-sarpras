@extends('layouts.app')
@section('title', 'Data Gedung')
@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <h4 class="mb-0">Data Gedung</h4>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('gedung.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Gedung</th>
                            <th>Alamat</th>
                            <th>Lahan</th>
                            @if(auth()->user()->isAdmin())
                                <th width="120">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gedung as $item)
                            <tr>
                                <td>{{ $item->nama_gedung }}</td>
                                <td>{{ $item->alamat_gedung ?? '-' }}</td>
                                <td>{{ $item->lahan->nama_lahan ?? '-' }}</td>
                                @if(auth()->user()->isAdmin())
                                    <td>
                                        <a href="{{ route('gedung.edit', $item) }}" class="btn btn-sm btn-warning"><i
                                                class="bi bi-pencil"></i></a>
                                        <form action="{{ route('gedung.destroy', $item) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                @endif
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
        $(document).ready(function () {
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
                }
                    @if(auth()->user()->isAdmin())
                        , columnDefs: [
                            { orderable: false, targets: [3] }
                        ]
                    @endif
                });
            });
    </script>
@endpush