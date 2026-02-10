<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SktMushalla extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $fillable = [
        'uuid',
        'nomor_id_mushalla',
        'nama_mushalla',
        'kecamatan_id',
        'kelurahan_id',
        'tipologi_mushalla_id',
        'alamat_mushalla',
        'file_skt',
        'file_barcode_mushalla',
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

    public function marbots()
    {
        return $this->hasMany(Marbot::class, 'rumah_ibadah_id')->where('tipe_rumah_ibadah', 'Mushalla');
    }

    public function tipologiMushalla(): BelongsTo
    {
        return $this->belongsTo(TipologiMushalla::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
