<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsetCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'aset_category_id');
    }
}
