<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'poster_url', 'video_url',
        'is_adult', 'release_year', 'duration',
        'authors', 'genres', 'views_count', 'likes_count', 'rating_avg'
    ];

    protected $casts = [
        'is_adult' => 'boolean',
        'authors' => 'array',
        'genres' => 'array',
        'rating_avg' => 'float',
    ];

    // Relación polimórfica: Comentarios
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Relación polimórfica: Likes
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Relación polimórfica: Ratings
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }
}
