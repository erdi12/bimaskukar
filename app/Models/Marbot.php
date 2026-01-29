<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Marbot extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'uuid',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'npwp',
        'nomor_rekening',
        'file_buku_rekening',
        'tanggal_mulai_bekerja',
        'no_hp',
        'alamat',
        'kecamatan_id',
        'kelurahan_id',
        'tipe_rumah_ibadah',
        'rumah_ibadah_id',
        'file_ktp',
        'file_kk',
        'file_sk_marbot',
        'file_npwp',
        'file_permohonan',
        'file_pernyataan',
        'nomor_induk_marbot',
        'status',
        'catatan',
        'verification_details',
        'status_umroh',
        'tahun_umroh',
        'bulan_umroh',
        'deadline_perbaikan',
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

    protected $casts = [
        'verification_details' => 'array',
        'tanggal_lahir' => 'date',
        'tanggal_mulai_bekerja' => 'date',
        'deadline_perbaikan' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function masjid()
    {
        return $this->belongsTo(SktMasjid::class, 'rumah_ibadah_id');
    }

    public function mushalla()
    {
        return $this->belongsTo(SktMushalla::class, 'rumah_ibadah_id');
    }

    public function getRumahIbadahAttribute()
    {
        if ($this->tipe_rumah_ibadah == 'Masjid') {
            return $this->masjid;
        }

        return $this->mushalla;
    }

    public function getJadwalKeberangkatanAttribute()
    {
        $bulan = '';
        if ($this->bulan_umroh) {
            $bulan = \Carbon\Carbon::create(null, (int) $this->bulan_umroh, 1)->locale('id')->monthName . ' ';
        }
        return $bulan . $this->tahun_umroh;
    }

    public function insentifs()
    {
        return $this->hasMany(MarbotInsentif::class);
    }
}
