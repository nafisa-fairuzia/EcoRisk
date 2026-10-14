<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteReportStatusHistory extends Model
{
    protected $fillable = [
        'status',
        'note',
    ];

    public function wasteReport(): BelongsTo
    {
        return $this->belongsTo(WasteReport::class);
    }
}
