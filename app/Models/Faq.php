<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasTranslations;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
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

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
