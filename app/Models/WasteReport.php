<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WasteReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo',
        'latitude',
        'longitude',
        'address',
        'description',
        'is_near_river',
        'is_dense_populated',
        'history_burned',
        'risk_score',
        'status',
        'priority_level',
        'kode_laporan',
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
        'petugas_id',
        'catatan_petugas',
        'foto_selesai_url',
        'tanggal_update_status',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_near_river' => 'boolean',
        'is_dense_populated' => 'boolean',
        'history_burned' => 'boolean',
        'risk_score' => 'decimal:2',
        'tanggal_update_status' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (WasteReport $wasteReport): void {
            $wasteReport->risk_score = $wasteReport->calculateRiskScore();
            $wasteReport->priority_level = $wasteReport->determinePriorityLevel(
                (int) $wasteReport->risk_score
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pelapor(): BelongsTo
    {
        return $this->user();
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function pickupLogs(): HasMany
    {
        return $this->hasMany(PickupLog::class);
    }

    public function wasteSorting(): HasOne
    {
        return $this->hasOne(WasteSorting::class);
    }

    public function calculateRiskScore(): int
    {
        $daysSinceReported = $this->created_at
            ? max(0, (int) $this->created_at->diffInDays(now(), false))
            : 0;

        return ($daysSinceReported * 10)
            + ($this->is_near_river ? 30 : 0)
            + ($this->is_dense_populated ? 20 : 0)
            + ($this->history_burned ? 25 : 0);
    }

    public function determinePriorityLevel(?int $riskScore = null): string
    {
        $riskScore ??= $this->calculateRiskScore();

        return match (true) {
            $riskScore < 30 => 'low',
            $riskScore < 60 => 'medium',
            $riskScore < 85 => 'high',
            default => 'critical',
        };
    }
}
