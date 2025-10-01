<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Unitseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'kode_unit' => '001',
            'nama_unit' => 'Palembang',
            'alamat_unit' => 'Jl jalan',
            'telepon_unit' => '0265311766',
            'lokasi_unit' => '-7.317623346580317,108.19935815408388',
            'radius_unit' => '30',
        ]);
    }
}
