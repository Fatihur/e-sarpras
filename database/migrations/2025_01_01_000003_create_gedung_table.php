<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gedung', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung');
            $table->text('alamat_gedung')->nullable();
            $table->foreignId('lahan_id')->nullable()->constrained('lahan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gedung');
    }
};
