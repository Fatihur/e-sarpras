@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <h4 class="mb-0">Data Barang</h4>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('barang.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</a>
        @endif
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
                                    @php $breakdown = $item->status_breakdown; @endphp
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($breakdown['aktif'] > 0)
                                            <span class="badge bg-success">{{ $breakdown['aktif'] }} Aktif</span>
                                        @endif
                                        @if($breakdown['dipinjam'] > 0)
                                            <span class="badge bg-warning text-dark">{{ $breakdown['dipinjam'] }} Dipinjam</span>
                                        @endif
                                        @if($breakdown['rusak'] > 0)
                                            <span class="badge bg-danger">{{ $breakdown['rusak'] }} Rusak</span>
                                        @endif
                                        @if($breakdown['tidak_bisa_diperbaiki'] > 0)
                                            <span class="badge bg-dark">{{ $breakdown['tidak_bisa_diperbaiki'] }} Disposed</span>
                                        @endif
                                        @if($breakdown['aktif'] == 0 && $breakdown['dipinjam'] == 0 && $breakdown['rusak'] == 0 && $breakdown['tidak_bisa_diperbaiki'] == 0)
                                            <span class="badge bg-secondary">Kosong</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-download-qr"
                                        data-url="{{ route('barang.download-qr', $item) }}" title="Download QR"><i
                                            class="bi bi-qr-code"></i></button>
                                </td>
                                <td>
                                    <a href="{{ route('barang.show', $item) }}" class="btn btn-sm btn-info"><i
                                            class="bi bi-eye"></i></a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('barang.edit', $item) }}" class="btn btn-sm btn-warning"><i
                                                class="bi bi-pencil"></i></a>
                                        <form action="{{ route('barang.destroy', $item) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
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
                },
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [5, 6] }
                ]
            });
        });

        document.querySelectorAll('.btn-download-qr').forEach(btn => {
            btn.addEventListener('click', function () {
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
        });
    </script>
@endpush