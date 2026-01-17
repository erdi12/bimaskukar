<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pegawai extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
    protected $table = 'pegawais';

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'foto',
        'sambutan',
        'is_kepala',
        'urutan',
    ];

    protected $casts = [
        'is_kepala' => 'boolean',
    ];
}
