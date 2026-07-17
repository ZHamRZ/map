<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Place extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'category',
        'latitude',
        'longitude',
        'description',
        'history',
        'cultural_significance',
        'video_url',
        'audio_url',
        'image_path',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(PlaceImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }
        if ($this->relationLoaded('images')) {
            $first = $this->images->first();
            return $first?->image_url;
        }
        return null;
    }
}
