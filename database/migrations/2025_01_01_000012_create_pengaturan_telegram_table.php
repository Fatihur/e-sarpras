<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_telegram', function (Blueprint $table) {
            $table->id();
            $table->string('bot_token')->nullable();
            $table->string('group_id')->nullable();
            $table->boolean('notif_peminjaman')->default(true);
            $table->boolean('notif_pengembalian')->default(true);
            $table->boolean('notif_barang_rusak')->default(true);
            $table->boolean('notif_barang_masuk')->default(false);
            $table->boolean('notif_barang_keluar')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_telegram');
    }
};
