<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteSorting extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_report_id',
        'organic_weight_kg',
        'inorganic_weight_kg',
        'paper_weight_kg',
        'channel_type',
        'prevented_carbon_emissions_kg',
        'prevented_burning_risk',
    ];

    protected $casts = [
        'organic_weight_kg' => 'decimal:2',
        'inorganic_weight_kg' => 'decimal:2',
        'paper_weight_kg' => 'decimal:2',
        'prevented_carbon_emissions_kg' => 'decimal:3',
        'prevented_burning_risk' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (WasteSorting $wasteSorting): void {
            $wasteSorting->prevented_carbon_emissions_kg = $wasteSorting->calculatePreventedEmissions();
        });
    }

    public function wasteReport(): BelongsTo
    {
        return $this->belongsTo(WasteReport::class);
    }

    public function calculatePreventedEmissions(): float
    {
        $totalWeightKg = (float) $this->organic_weight_kg
            + (float) $this->inorganic_weight_kg
            + (float) $this->paper_weight_kg;

        return round($totalWeightKg * 1.5, 3);
    }
}
