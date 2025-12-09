<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_notifikasi');
            $table->text('pesan');
            $table->enum('status', ['terkirim', 'gagal'])->default('terkirim');
            $table->text('response')->nullable();
            $table->dateTime('waktu_kirim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_notifikasi');
    }
};
