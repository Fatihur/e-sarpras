@extends('layouts.app')
@section('title', 'Laporan Keseluruhan')
@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <h4 class="mb-0">Laporan Keseluruhan</h4>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i>Filter</button>
                    <a href="{{ route('laporan.keseluruhan') }}" class="btn btn-outline-secondary">Reset</a>
                    <div class="btn-group ms-2">
                        <button type="submit" name="export" value="pdf" class="btn btn-danger"><i
                                class="bi bi-file-earmark-pdf me-1"></i>Export PDF</button>
                        <button type="submit" name="export" value="excel" class="btn btn-success"><i
                                class="bi bi-file-earmark-excel me-1"></i>Export Excel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-lg mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-in-down display-6"></i>
                    <h3 class="mb-0 mt-2">{{ count($barangMasuk) }}</h3>
                    <small>Barang Masuk</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg mb-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-up display-6"></i>
                    <h3 class="mb-0 mt-2">{{ count($barangKeluar) }}</h3>
                    <small>Barang Keluar</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-left-right display-6"></i>
                    <h3 class="mb-0 mt-2">{{ count($peminjaman) }}</h3>
                    <small>Peminjaman</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle display-6"></i>
                    <h3 class="mb-0 mt-2">{{ count($barangRusak) }}</h3>
                    <small>Barang Rusak</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-door-open display-6"></i>
                    <h3 class="mb-0 mt-2">{{ count($barangRuangan) }}</h3>
                    <small>Barang Ruangan</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs for each report --}}
    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabMasuk">Barang Masuk</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabKeluar">Barang Keluar</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabPinjam">Peminjaman</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabRusak">Barang Rusak</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabRuangan">Barang Ruangan</a></li>
    </ul>

    <div class="tab-content border border-top-0 rounded-bottom p-3 bg-white">
        {{-- Barang Masuk --}}
        <div class="tab-pane fade show active" id="tabMasuk">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Sumber</th>
                            <th class="text-end">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangMasuk as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->sumber_barang ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Barang Keluar --}}
        <div class="tab-pane fade" id="tabKeluar">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Alasan</th>
                            <th>Penerima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangKeluar as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->alasan_keluar }}</td>
                                <td>{{ $item->penerima ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Peminjaman --}}
        <div class="tab-pane fade" id="tabPinjam">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pinjam</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Peminjam</th>
                            <th>Status</th>
                            <th>Dikembalikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->nama_peminjam }}</td>
                                <td>
                                    @if($item->status == 'dipinjam')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>{{ $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Barang Rusak --}}
        <div class="tab-pane fade" id="tabRusak">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Jenis Kerusakan</th>
                            <th>Lokasi</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangRusak as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->jenis_kerusakan }}</td>
                                <td>{{ $item->lokasi == 'dalam_ruangan' ? 'Dalam' : 'Luar' }}</td>
                                <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Barang Ruangan --}}
        <div class="tab-pane fade" id="tabRuangan">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Ruangan</th>
                            <th>Nama Ruangan</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangRuangan as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->ruangan->kode_ruangan ?? '-' }}</td>
                                <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->jumlah }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection