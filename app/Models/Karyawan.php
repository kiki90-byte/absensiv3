<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = "karyawan";
    protected $primaryKey = "nik";
    public $incrementing = false;
    protected $guarded = [];

    function getRekapstatuskaryawan($request = null)
    {
        $query = Karyawan::query();
        $query->select(
            DB::raw("SUM(IF(status_karyawan = 'K', 1, 0)) as jml_PKWT"),
            DB::raw("SUM(IF(status_karyawan = 'T', 1, 0)) as jml_PKWTT"),
            DB::raw("SUM(IF(status_aktif_karyawan = '1', 1, 0)) as jml_aktif"),
        );
        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_div)) {
            $query->where('karyawan.kode_div', $request->kode_div);
        }
        return $query->first();
    }

    // Relasi dengan Facerecognition
    public function facerecognition()
    {
        return $this->hasMany(Facerecognition::class, 'nik', 'nik');
    }
}
