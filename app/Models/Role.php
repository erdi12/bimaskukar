<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Relasi Many-to-Many dengan User
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
