<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete();
            $table->string('satuan')->default('unit');
            $table->integer('jumlah')->default(0);
            $table->decimal('nilai_aset', 15, 2)->default(0);
            $table->enum('status_barang', ['aktif', 'rusak', 'hilang', 'keluar', 'dipinjam'])->default('aktif');
            $table->string('foto_barang')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
