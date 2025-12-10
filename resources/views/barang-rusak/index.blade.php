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
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusColors = [
                            'dilaporkan' => 'warning',
                            'diproses' => 'info', 
                            'diperbaiki' => 'success',
                            'tidak_bisa_diperbaiki' => 'danger'
                        ];
                        $statusLabels = [
                            'dilaporkan' => 'Dilaporkan',
                            'diproses' => 'Diproses',
                            'diperbaiki' => 'Diperbaiki',
                            'tidak_bisa_diperbaiki' => 'Tidak Bisa Diperbaiki'
                        ];
                    @endphp
                    @foreach($barangRusak as $item)
                    <tr>
                        <td data-order="{{ $item->tanggal_rusak->format('Y-m-d') }}">{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $item->barang->nama_barang ?? '-' }}</strong>
                            <br><small class="text-muted">{{ $item->barang->kode_barang ?? '' }}</small>
                        </td>
                        <td>{{ $item->jenis_kerusakan }}</td>
                        <td>
                            <span class="badge bg-{{ $item->lokasi == 'dalam_ruangan' ? 'info' : 'secondary' }}">
                                {{ $item->lokasi == 'dalam_ruangan' ? 'Dalam Ruangan' : 'Luar Ruangan' }}
                            </span>
                            @if($item->ruangan)
                            <br><small class="text-muted">{{ $item->ruangan->nama_ruangan }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $statusColors[$item->status ?? 'dilaporkan'] }}">
                                {{ $statusLabels[$item->status ?? 'dilaporkan'] }}
                            </span>
                            @if($item->tanggal_update_status)
                            <br><small class="text-muted">{{ $item->tanggal_update_status->format('d/m/Y') }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $item->id }}" title="Update Status">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('barang-rusak.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
@foreach($barangRusak as $item)
<div class="modal fade" id="modalStatus{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('barang-rusak.update-status', $item) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Update Status Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <strong>{{ $item->barang->nama_barang ?? '-' }}</strong><br>
                        <small>Kerusakan: {{ $item->jenis_kerusakan }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Perbaikan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="dilaporkan" {{ ($item->status ?? 'dilaporkan') == 'dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                            <option value="diproses" {{ ($item->status ?? '') == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="diperbaiki" {{ ($item->status ?? '') == 'diperbaiki' ? 'selected' : '' }}>Sudah Diperbaiki</option>
                            <option value="tidak_bisa_diperbaiki" {{ ($item->status ?? '') == 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan_status" class="form-control" rows="3" placeholder="Catatan perbaikan...">{{ $item->catatan_status }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
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
