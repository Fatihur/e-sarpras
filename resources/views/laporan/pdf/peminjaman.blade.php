<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Barang</h2>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr><th>No</th><th>Tanggal Pinjam</th><th>Barang</th><th>Peminjam</th><th>Status</th><th>Dikembalikan</th></tr>
        </thead>
        <tbody>
            @foreach($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
