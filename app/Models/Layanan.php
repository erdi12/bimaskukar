<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Layanan extends Model
{
    use LogsActivity;

    protected $table = 'layanans';

    protected $fillable = [
        'judul',
        'slug',
        'ikon',
        'deskripsi_singkat',
        'konten'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
