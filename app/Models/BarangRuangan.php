<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangRuangan extends Model
{
    public $timestamps = false;
    protected $table = 'barang_ruangan';

    protected $fillable = [
        'barang_id',
        'ruangan_id',
        'jumlah',
        'keterangan',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }
}
