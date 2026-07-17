<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'place_id',
        'visitor_name',
        'rating',
        'comment',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
