<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SktRumahIbadah extends Model
{
    use SoftDeletes;

    protected $table = 'skt_rumah_ibadahs';

    protected $fillable = [
        'nomor_statistik',
        'nama_rumah_ibadah',
        'alamat',
        'kecamatan_id',
        'kelurahan_id',
        'jenis_rumah_ibadah_id',
        'tipologi_rumah_ibadah_id'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function jenisrumahibadah()
    {
        return $this->belongsTo(JenisRumahIbadah::class);
    }

    public function tipologirumahibadah()
    {
        return $this->belongsTo(TipologiRumahIbadah::class);
    }
}
