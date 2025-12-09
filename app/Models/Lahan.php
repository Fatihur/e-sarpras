<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lahan extends Model
{
    public $timestamps = false;
    protected $table = 'lahan';

    protected $fillable = [
        'kode_lahan',
        'nama_lahan',
        'lokasi_lahan',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_lahan)) {
                $model->kode_lahan = self::generateKode();
            }
        });
    }

    public static function generateKode(): string
    {
        $lastRecord = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastRecord ? (int) substr($lastRecord->kode_lahan, -4) : 0;
        return 'LHN-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function gedung(): HasMany
    {
        return $this->hasMany(Gedung::class);
    }
}
