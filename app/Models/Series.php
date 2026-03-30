<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'poster_url',
        'banner_url', // Permitido
        'type',
        'release_year',
        'authors',
        'is_adult',
        'views_count',
        'likes_count',
        'rating_avg'
    ];

    protected $casts = [
        'is_adult' => 'boolean',
        'authors' => 'array',
        'rating_avg' => 'float',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function episodes()
    {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
