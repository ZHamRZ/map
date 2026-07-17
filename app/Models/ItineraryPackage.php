<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ItineraryPackage extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'image_path',
        'duration',
        'category_list',
        'translations',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'category_list' => 'array',
            'translations' => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ItineraryPackage $pkg) {
            if (empty($pkg->slug)) {
                $pkg->slug = Str::slug($pkg->title);
            }
        });
    }

    public function places()
    {
        return $this->belongsToMany(Place::class, 'itinerary_package_place')
            ->withPivot('order')
            ->orderBy('itinerary_package_place.order');
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
}
