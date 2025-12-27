<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang_rusak', function (Blueprint $table) {
            $table->integer('jumlah')->default(1)->after('deskripsi_kerusakan');
        });
    }

    public function down(): void
    {
        Schema::table('barang_rusak', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });
    }
};
