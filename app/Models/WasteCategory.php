<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'kelompok',
        'instruksi_penanganan',
        'potensi_penyaluran',
        'status',
        'name',
        'type',
        'carbon_offset_factor_per_kg',
    ];
}
