<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gedung extends Model
{
    public $timestamps = false;
    protected $table = 'gedung';

    protected $fillable = [
        'nama_gedung',
        'alamat_gedung',
        'lahan_id',
    ];

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }

    public function ruangan(): HasMany
    {
        return $this->hasMany(Ruangan::class);
    }
}
