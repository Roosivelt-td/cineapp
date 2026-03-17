<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'poster_url', 'type',
        'release_year', 'authors', 'genres', 'is_adult',
        'views_count', 'likes_count', 'rating_avg'
    ];

    protected $casts = [
        'is_adult' => 'boolean',
        'authors' => 'array',
        'genres' => 'array',
        'rating_avg' => 'float',
    ];

    // Relación: Una serie tiene muchas temporadas
    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    // Relación a través de las temporadas: Una serie tiene muchos capítulos
    public function episodes()
    {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    // Relación polimórfica: Ratings (las series se califican como un todo)
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    // Relación polimórfica: Likes (se puede dar like a una serie)
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
