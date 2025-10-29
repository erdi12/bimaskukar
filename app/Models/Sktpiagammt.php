<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sktpiagammt extends Model
{
    use SoftDeletes;

    protected $table = 'sktpiagammts';

    protected $fillable = [
        'nomor_statistik',
        'nama_majelis',
        'alamat',
        'kecamatan_id',
        'kelurahan_id',
        'tanggal_berdiri',
        'status',
        'ketua',
        'no_hp',
        'mendaftar',
        'mendaftar_ulang',
        'file_skt',
        'file_piagam',
        'file_berkas', // Tambahkan ini
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
