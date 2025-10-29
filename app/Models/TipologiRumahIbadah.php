<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipologiRumahIbadah extends Model
{
    use SoftDeletes;

    protected $table = 'tipologi_rumah_ibadahs';

    protected $fillable = [
        'nama_tipologi',
        'jenis_rumah_ibadah_id'
    ];

    public function jenisrumahibadah()
    {
        return $this->belongsTo(JenisRumahIbadah::class);
    }

    public function sktrumahibadah()
    {
        return $this->hasMany(SktRumahIbadah::class);
    }
}
