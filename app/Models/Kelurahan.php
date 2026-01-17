<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends Model
{
     use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $table = 'kelurahans';

    protected $fillable = [
        'uuid',
        'nama_kelurahan',
        'jenis_kelurahan', // Added
        'kecamatan_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    // Relasi ke SktPiagamMt
    public function sktpiagammts()
    {
        return $this->hasMany(Sktpiagammt::class);
    }

    public function sktrumahibadah()
    {
        return $this->hasMany(SktRumahIbadah::class);
    }
}
