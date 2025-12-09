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
        'user_id',
    ];

    protected $casts = [
        'tanggal_rusak' => 'date',
    ];

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
