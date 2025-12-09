<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lahan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lahan')->unique();
            $table->string('nama_lahan');
            $table->string('lokasi_lahan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lahan');
    }
};
