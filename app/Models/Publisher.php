<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'country',
        'website',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
