@extends('layouts.app')
@section('title', 'Pengaturan Telegram')
@section('content')
<h4 class="mb-4">Pengaturan Telegram</h4>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-telegram me-2"></i>Konfigurasi Bot</div>
            <div class="card-body">
                <form method="POST" action="{{ route('telegram.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Bot Token</label>
                        <input type="text" name="bot_token" class="form-control" value="{{ $pengaturan->bot_token }}" placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Group/Chat ID</label>
                        <input type="text" name="group_id" class="form-control" value="{{ $pengaturan->group_id }}" placeholder="-1001234567890">
                    </div>
                    <hr>
                    <p class="text-muted small">Aktifkan notifikasi untuk:</p>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="notif_peminjaman" class="form-check-input" id="notif1" {{ $pengaturan->notif_peminjaman ? 'checked' : '' }}>
                        <label class="form-check-label" for="notif1">Peminjaman Barang</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="notif_pengembalian" class="form-check-input" id="notif2" {{ $pengaturan->notif_pengembalian ? 'checked' : '' }}>
                        <label class="form-check-label" for="notif2">Pengembalian Barang</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="notif_barang_rusak" class="form-check-input" id="notif3" {{ $pengaturan->notif_barang_rusak ? 'checked' : '' }}>
                        <label class="form-check-label" for="notif3">Barang Rusak</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="notif_barang_masuk" class="form-check-input" id="notif4" {{ $pengaturan->notif_barang_masuk ? 'checked' : '' }}>
                        <label class="form-check-label" for="notif4">Barang Masuk</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="notif_barang_keluar" class="form-check-input" id="notif5" {{ $pengaturan->notif_barang_keluar ? 'checked' : '' }}>
                        <label class="form-check-label" for="notif5">Barang Keluar</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                    </div>
                </form>
                <form action="{{ route('telegram.test') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-info btn-sm"><i class="bi bi-send me-1"></i>Test Notifikasi</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Log Notifikasi</div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Waktu</th><th>Tipe</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="small">{{ $log->waktu_kirim->format('d/m H:i') }}</td>
                            <td class="small">{{ $log->tipe_notifikasi }}</td>
                            <td><span class="badge bg-{{ $log->status == 'terkirim' ? 'success' : 'danger' }} small">{{ $log->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada log</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
