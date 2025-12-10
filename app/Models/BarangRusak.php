<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangRusak extends Model
{
    public $timestamps = false;
    protected $table = 'barang_rusak';

    protected $fillable = [
        'barang_id',
        'ruangan_id',
        'tanggal_rusak',
        'jenis_kerusakan',
        'deskripsi_kerusakan',
        'foto_bukti',
        'lokasi',
        'status',
        'catatan_status',
        'tanggal_update_status',
        'user_id',
    ];

    protected $casts = [
        'tanggal_rusak' => 'date',
        'tanggal_update_status' => 'datetime',
    ];

    public static function getStatusOptions(): array
    {
        return [
            'dilaporkan' => 'Dilaporkan',
            'diproses' => 'Sedang Diproses',
            'diperbaiki' => 'Sudah Diperbaiki',
            'tidak_bisa_diperbaiki' => 'Tidak Bisa Diperbaiki',
        ];
    }

    public static function getStatusColor(string $status): string
    {
        return match ($status) {
            'dilaporkan' => 'warning',
            'diproses' => 'info',
            'diperbaiki' => 'success',
            'tidak_bisa_diperbaiki' => 'danger',
            default => 'secondary',
        };
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
