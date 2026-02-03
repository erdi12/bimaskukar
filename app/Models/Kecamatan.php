<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Kecamatan extends Model
{
    use LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $table = 'kecamatans';

    protected $fillable = [
        'uuid',
        'kecamatan',
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

    // Accessor untuk mengkapitalisasi nama kecamatan
    // protected function kecamatan(): \Illuminate\Database\Eloquent\Casts\Attribute
    // {
    //     return \Illuminate\Database\Eloquent\Casts\Attribute::make(
    //         get: fn ($value) => ucwords(strtolower($value))
    //     );
    // }

    // Relasi ke Kelurahan
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
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

    public function masjids()
    {
        return $this->hasMany(SktMasjid::class);
    }

    public function mushallas()
    {
        return $this->hasMany(SktMushalla::class);
    }
}
