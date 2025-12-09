<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Barang extends Model
{
    public $timestamps = false;
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan',
        'jumlah',
        'nilai_aset',
        'status_barang',
        'foto_barang',
    ];

    protected $casts = [
        'nilai_aset' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_barang)) {
                $model->kode_barang = self::generateKode();
            }
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ruangan(): BelongsToMany
    {
        return $this->belongsToMany(Ruangan::class, 'barang_ruangan')
            ->withPivot('jumlah', 'keterangan');
    }


    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function barangRusak(): HasMany
    {
        return $this->hasMany(BarangRusak::class);
    }

    /**
     * Generate QR Code on-the-fly (tidak disimpan di database)
     */
    public function getQrCodeBase64Attribute(): string
    {
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($this->kode_barang);
        
        return base64_encode($qrCodeSvg);
    }

    /**
     * Get QR Code as data URI untuk ditampilkan di img src
     */
    public function getQrCodeImageAttribute(): string
    {
        return 'data:image/svg+xml;base64,' . $this->qr_code_base64;
    }

    public static function generateKode(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $lastBarang = self::where('kode_barang', 'like', "INV-{$tahun}{$bulan}-%")
            ->orderBy('kode_barang', 'desc')
            ->first();
        
        if ($lastBarang) {
            $lastNumber = (int) substr($lastBarang->kode_barang, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "INV-{$tahun}{$bulan}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
