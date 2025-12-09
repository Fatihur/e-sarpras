@extends('layouts.app')
@section('title', 'Data User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data User</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah User</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th width="180">Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                    <td><span class="badge bg-{{ $user->aktif ? 'success' : 'secondary' }}">{{ $user->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-{{ $user->aktif ? 'secondary' : 'success' }}" title="{{ $user->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bi bi-{{ $user->aktif ? 'x-circle' : 'check-circle' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('users.reset-password', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset password?')">
                            @csrf
                            <button class="btn btn-sm btn-info" title="Reset Password"><i class="bi bi-key"></i></button>
                        </form>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="card-footer">{{ $users->links() }}</div>@endif
</div>
@endsection
