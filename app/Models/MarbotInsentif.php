<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarbotInsentif extends Model
{
    use HasFactory;

    protected $fillable = [
        'marbot_id',
        'tahun_anggaran',
        'bulan',
        'nominal',
        'tanggal_terima',
        'keterangan',
    ];

    public function marbot()
    {
        return $this->belongsTo(Marbot::class);
    }
}
