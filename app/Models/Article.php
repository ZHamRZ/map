<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'category',
        'cover_image',
        'author',
        'translations',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image
            ? \Illuminate\Support\Facades\Storage::url($this->cover_image)
            : null;
    }
}
