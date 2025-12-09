<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    public $timestamps = false;
    protected $table = 'barang_masuk';

    protected $fillable = [
        'tanggal_masuk',
        'barang_id',
        'jumlah',
        'sumber_barang',
        'harga',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga' => 'decimal:2',
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
