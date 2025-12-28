<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keseluruhan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #0d9488;
        }

        h3 {
            background: #0d9488;
            color: white;
            padding: 8px;
            margin: 20px 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }

        .header-info {
            margin-bottom: 20px;
            text-align: center;
        }

        .summary-box {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
    </style>
</head>

<body>
    <div class="header-info">
        <h2>LAPORAN KESELURUHAN INVENTARIS</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
        @if($tanggalDari || $tanggalSampai)
            <p>Periode: {{ $tanggalDari ? \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') : 'Awal' }} -
                {{ $tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') : 'Sekarang' }}</p>
        @endif
    </div>

    <div class="summary-box">
        <strong>Ringkasan:</strong><br>
        Barang Masuk: {{ count($barangMasuk) }} transaksi |
        Barang Keluar: {{ count($barangKeluar) }} transaksi |
        Peminjaman: {{ count($peminjaman) }} transaksi |
        Barang Rusak: {{ count($barangRusak) }} laporan |
        Barang Ruangan: {{ count($barangRuangan) }} item
    </div>

    {{-- BARANG MASUK --}}
    <h3>1. BARANG MASUK</h3>
    @if(count($barangMasuk) > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Sumber</th>
                    <th class="text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangMasuk as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->sumber_barang ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Tidak ada data barang masuk</em></p>
    @endif

    {{-- BARANG KELUAR --}}
    <div class="page-break"></div>
    <h3>2. BARANG KELUAR</h3>
    @if(count($barangKeluar) > 0)
        <table>
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
                @foreach($barangKeluar as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->alasan_keluar }}</td>
                        <td>{{ $item->penerima ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Tidak ada data barang keluar</em></p>
    @endif

    {{-- PEMINJAMAN --}}
    <div class="page-break"></div>
    <h3>3. PEMINJAMAN</h3>
    @if(count($peminjaman) > 0)
        <table>
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
                @foreach($peminjaman as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->nama_peminjam }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>{{ $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Tidak ada data peminjaman</em></p>
    @endif

    {{-- BARANG RUSAK --}}
    <div class="page-break"></div>
    <h3>4. BARANG RUSAK</h3>
    @if(count($barangRusak) > 0)
        <table>
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
                @foreach($barangRusak as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jenis_kerusakan }}</td>
                        <td>{{ $item->lokasi == 'dalam_ruangan' ? 'Dalam Ruangan' : 'Luar Ruangan' }}</td>
                        <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Tidak ada data barang rusak</em></p>
    @endif

    {{-- BARANG RUANGAN --}}
    <div class="page-break"></div>
    <h3>5. BARANG PER RUANGAN</h3>
    @if(count($barangRuangan) > 0)
        <table>
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
                @foreach($barangRuangan as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->ruangan->kode_ruangan ?? '-' }}</td>
                        <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Tidak ada data barang ruangan</em></p>
    @endif

</body>

</html>