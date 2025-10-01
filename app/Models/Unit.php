<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = "unit";
    protected $primaryKey = "kode_unit";
    public $incrementing = false;
    protected $guarded = [];
}
