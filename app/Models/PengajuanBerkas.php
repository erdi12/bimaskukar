<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengajuanBerkas extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_berkas';

    protected $fillable = [
        'uuid',
        'kode_tiket',
        'seleksi_berkas_id',
        'nama_pengaju',
        'no_hp',
        'data_isian',
        'berkas_files',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'data_isian'   => 'array',
        'berkas_files' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->kode_tiket)) {
                $model->kode_tiket = self::generateKodeTiket();
            }
        });
    }

    /**
     * Generate kode tiket unik.
     */
    public static function generateKodeTiket(): string
    {
        do {
            $kode = strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(3));
        } while (self::where('kode_tiket', $kode)->exists());

        return $kode;
    }

    public function seleksiBerkas()
    {
        return $this->belongsTo(SeleksiBerkas::class);
    }

    /**
     * Badge color berdasarkan status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'warning',
            'diproses' => 'info',
            'diterima' => 'success',
            'ditolak'  => 'danger',
            default    => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
            default    => 'Tidak Diketahui',
        };
    }
}
