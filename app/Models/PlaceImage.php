<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PlaceImage extends Model
{
    protected $fillable = [
        'place_id',
        'image_path',
        'type',
        'mime_type',
        'file_hash',
        'file_size',
        'thumb_path',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        return $this->thumb_path
            ? Storage::disk('public')->url($this->thumb_path)
            : null;
    }

    public function scopeImages($q)
    {
        return $q->where('type', 'image');
    }

    public function scopeVideos($q)
    {
        return $q->where('type', 'video');
    }
}
