<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $fillable = [
        'author_id',
        'publisher_id',
        'title',
        'slug',
        'isbn',
        'publication_year',
        'format',
        'stock',
        'summary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function imageUrl(): string
    {
        $code = $this->isbn ? Str::lower($this->isbn) : Str::slug($this->title);
        $path = "images/products/{$code}.svg";

        if (! file_exists(public_path($path))) {
            $path = 'images/products/default.svg';
        }

        return asset($path);
    }
}
