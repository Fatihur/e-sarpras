<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanTelegram extends Model
{
    public $timestamps = false;
    protected $table = 'pengaturan_telegram';

    protected $fillable = [
        'bot_token',
        'group_id',
        'notif_peminjaman',
        'notif_pengembalian',
        'notif_barang_rusak',
        'notif_barang_masuk',
        'notif_barang_keluar',
    ];

    protected $casts = [
        'notif_peminjaman' => 'boolean',
        'notif_pengembalian' => 'boolean',
        'notif_barang_rusak' => 'boolean',
        'notif_barang_masuk' => 'boolean',
        'notif_barang_keluar' => 'boolean',
    ];
}
