<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ruangan extends Model
{
    public $timestamps = false;
    protected $table = 'ruangan';

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'gedung_id',
        'penanggung_jawab',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_ruangan)) {
                $model->kode_ruangan = self::generateKode();
            }
        });
    }

    public static function generateKode(): string
    {
        $lastRecord = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastRecord ? (int) substr($lastRecord->kode_ruangan, -4) : 0;
        return 'RNG-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class);
    }

    public function barang(): BelongsToMany
    {
        return $this->belongsToMany(Barang::class, 'barang_ruangan')
            ->withPivot('jumlah', 'keterangan');
    }

    public function barangRuangan(): HasMany
    {
        return $this->hasMany(BarangRuangan::class);
    }

    public function barangRusak(): HasMany
    {
        return $this->hasMany(BarangRusak::class);
    }
}
