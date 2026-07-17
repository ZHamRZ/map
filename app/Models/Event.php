<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'latitude',
        'longitude',
        'category',
        'image_path',
        'video_url',
        'translations',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'start_time' => 'string',
            'end_time' => 'string',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())->orderBy('start_date');
    }

    public function scopePast($query)
    {
        return $query->where('start_date', '<', now())->orderByDesc('start_date');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path)
            : null;
    }

    public function getTranslatedTitleAttribute(): string
    {
        return $this->getTranslated('title', app()->getLocale()) ?? $this->title;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->getTranslated('description', app()->getLocale()) ?? $this->description;
    }
}
