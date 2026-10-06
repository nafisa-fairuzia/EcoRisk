<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_report_id',
        'officer_id',
        'pickup_time',
        'status',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
    ];

    public function wasteReport(): BelongsTo
    {
        return $this->belongsTo(WasteReport::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
