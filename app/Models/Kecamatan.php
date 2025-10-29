<?php

namespace App\Models;

use App\Models\Kelurahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use SoftDeletes;

    protected $table = 'kecamatans';

    protected $fillable = [
        'kecamatan',
    ];

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
        return $this->hasMany(SktPiagamMt::class);
    }

    public function sktrumahibadah()
    {
        return $this->hasMany(SktRumahIbadah::class);
    }
}
