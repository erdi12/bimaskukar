<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisRumahIbadah extends Model
{
    use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $table = 'jenis_rumah_ibadahs';

    protected $fillable = [
        'jenis_rumah_ibadah'
    ];

    public function sktrumahibadah()
    {
        return $this->hasMany(SktRumahIbadah::class);
    }

    public function tipologirumahibadah()
    {
        return $this->belongsTo(TipologiRumahIbadah::class);
    }
}
