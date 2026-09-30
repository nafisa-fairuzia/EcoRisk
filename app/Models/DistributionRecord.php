<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat_jalan',
        'petugas_id',
        'tanggal',
        'mitra_tujuan',
        'kategori_sampah',
        'berat_kg',
        'catatan',
        'status_serah_terima',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'berat_kg' => 'decimal:2',
    ];

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
