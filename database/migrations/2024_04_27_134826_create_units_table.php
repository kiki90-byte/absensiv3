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
        Schema::create('unit', function (Blueprint $table) {
            $table->char('kode_unit', 3)->primary();
            $table->string('nama_unit', 50);
            $table->string('alamat_unit', 100);
            $table->string('telepon_unit', 13);
            $table->string('lokasi_unit');
            $table->smallInteger('radius_unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit');
    }
};
