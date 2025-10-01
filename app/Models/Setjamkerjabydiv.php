<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setjamkerjabydiv extends Model
{
    use HasFactory;
    protected $table = 'presensi_jamkerja_bydiv';
    protected $guarded = [];
    protected $primaryKey = 'kode_jk_div';
    public $incrementing = false;
}
