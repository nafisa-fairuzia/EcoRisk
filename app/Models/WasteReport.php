<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_laporan',
        'user_id',
        'nama_pelapor',
        'telepon_pelapor',
        'alamat',
        'rt',
        'rw',
        'patokan',
        'jenis_tumpukan',
        'deskripsi',
        'foto_url',
        'urgensi',
        'status',
        'petugas_id',
        'catatan_petugas',
        'foto_selesai_url',
        'tanggal_update_status',
    ];

    protected $casts = [
        'tanggal_update_status' => 'datetime',
    ];

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
