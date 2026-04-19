<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeleksiBerkas extends Model
{
    use HasFactory;

    protected $table = 'seleksi_berkas';

    protected $fillable = [
        'uuid',
        'judul',
        'slug',
        'deskripsi',
        'field_configs',
        'berkas_configs',
        'tanggal_buka',
        'tanggal_tutup',
        'is_active',
    ];

    protected $casts = [
        'field_configs'  => 'array',
        'berkas_configs' => 'array',
        'tanggal_buka'   => 'date',
        'tanggal_tutup'  => 'date',
        'is_active'      => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function pengajuans()
    {
        return $this->hasMany(PengajuanBerkas::class);
    }

    /**
     * Apakah seleksi sedang buka (aktif & dalam rentang tanggal).
     */
    public function isBuka(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now()->startOfDay();

        if ($this->tanggal_buka && $now->lt($this->tanggal_buka)) {
            return false;
        }

        if ($this->tanggal_tutup && $now->gt($this->tanggal_tutup)) {
            return false;
        }

        return true;
    }
}
