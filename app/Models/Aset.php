<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Aset extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = [
        'aset_category_id', 'asset_code', 'name', 'brand', 'model', 'serial_number',
        'acquisition_date', 'acquisition_source', 'acquisition_value', 'condition',
        'status', 'location', 'responsible_person', 'specifications', 'notes',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'acquisition_value' => 'decimal:2',
        'specifications' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AsetCategory::class, 'aset_category_id');
    }
}
