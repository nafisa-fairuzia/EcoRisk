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

        static::created(function (WasteReport $wasteReport): void {
            $wasteReport->statusHistories()->create([
                'status' => $wasteReport->status ?? 'reported',
                'note' => $wasteReport->catatan_petugas,
            ]);
        });

        static::updated(function (WasteReport $wasteReport): void {
            if ($wasteReport->wasChanged('status')) {
                $wasteReport->statusHistories()->create([
                    'status' => $wasteReport->status,
                    'note' => $wasteReport->catatan_petugas,
                ]);
            }
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

    public function statusHistories(): HasMany
    {
        return $this->hasMany(WasteReportStatusHistory::class)
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function wasteSorting(): HasOne
    {
        return $this->hasOne(WasteSorting::class);
    }

    public function canBeEditedByResident(): bool
    {
        return in_array($this->status, ['reported', 'approved'], true)
            && $this->petugas_id === null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'reported', 'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'in_process' => 'Diproses',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Status tidak diketahui',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority_level) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
            default => '-',
        };
    }

    public function getUrgencyLabelAttribute(): string
    {
        return match ($this->urgensi) {
            'tinggi' => 'Tinggi',
            'sedang' => 'Sedang',
            'rendah' => 'Rendah',
            default => 'Belum ditentukan',
        };
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
