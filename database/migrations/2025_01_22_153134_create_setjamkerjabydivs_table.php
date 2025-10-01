<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi_jamkerja_bydiv', function (Blueprint $table) {
            $table->char('kode_jk_div', 7)->primary();
            $table->char('kode_unit', 3);
            $table->char('kode_div', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_jamkerja_bydiv');
    }
};
