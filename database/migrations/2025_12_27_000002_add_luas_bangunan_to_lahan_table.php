<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            $table->decimal('luas_bangunan', 12, 2)->nullable()->after('lokasi_lahan');
        });
    }

    public function down(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            $table->dropColumn('luas_bangunan');
        });
    }
};
