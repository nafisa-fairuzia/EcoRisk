<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SortingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pilah',
        'petugas_id',
        'report_id',
        'tanggal',
        'lokasi_tps',
        'organik_kg',
        'plastik_kg',
        'kertas_karton_kg',
        'logam_kaca_kg',
        'residu_b3_kg',
        'total_kg',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'organik_kg' => 'decimal:2',
        'plastik_kg' => 'decimal:2',
        'kertas_karton_kg' => 'decimal:2',
        'logam_kaca_kg' => 'decimal:2',
        'residu_b3_kg' => 'decimal:2',
        'total_kg' => 'decimal:2',
    ];

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(WasteReport::class, 'report_id');
    }
}
