<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_rusak', function (Blueprint $table) {
            $table->enum('status', ['dilaporkan', 'diproses', 'diperbaiki', 'tidak_bisa_diperbaiki'])->default('dilaporkan')->after('lokasi');
            $table->text('catatan_status')->nullable()->after('status');
            $table->timestamp('tanggal_update_status')->nullable()->after('catatan_status');
        });
    }

    public function down(): void
    {
        Schema::table('barang_rusak', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_status', 'tanggal_update_status']);
        });
    }
};
