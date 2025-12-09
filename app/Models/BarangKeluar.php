<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    public $timestamps = false;
    protected $table = 'barang_keluar';

    protected $fillable = [
        'tanggal_keluar',
        'barang_id',
        'jumlah',
        'alasan_keluar',
        'penerima',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
