<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'full_name',
        'nationality',
        'birth_date',
        'biography',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
