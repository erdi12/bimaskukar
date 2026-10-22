<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsetDocument extends Model
{
    protected $fillable = ['aset_id', 'original_name', 'file_path', 'mime_type', 'file_size'];

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }
}
