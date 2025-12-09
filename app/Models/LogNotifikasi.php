<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogNotifikasi extends Model
{
    public $timestamps = false;
    protected $table = 'log_notifikasi';

    protected $fillable = [
        'tipe_notifikasi',
        'pesan',
        'status',
        'response',
        'waktu_kirim',
    ];

    protected $casts = [
        'waktu_kirim' => 'datetime',
    ];
}
