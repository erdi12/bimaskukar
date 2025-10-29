<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends Model
{
     use SoftDeletes;

    protected $table = 'kelurahans';

    protected $fillable = [
        'nama_kelurahan',
        'kecamatan_id',
    ];

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
