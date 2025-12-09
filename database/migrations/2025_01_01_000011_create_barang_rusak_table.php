<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_rusak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->nullOnDelete();
            $table->date('tanggal_rusak');
            $table->string('jenis_kerusakan');
            $table->text('deskripsi_kerusakan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->enum('lokasi', ['dalam_ruangan', 'luar_ruangan'])->default('dalam_ruangan');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_rusak');
    }
};
