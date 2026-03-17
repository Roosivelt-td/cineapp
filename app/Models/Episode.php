<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id', 'title', 'description', 'number',
        'video_url', 'duration', 'views_count', 'likes_count',
    ];

    // Relación: Un capítulo pertenece a una temporada
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    // Acceso directo a la serie desde el episodio
    public function series()
    {
        return $this->hasOneThrough(Series::class, Season::class, 'id', 'id', 'season_id', 'series_id');
    }

    // Relación polimórfica: Comentarios (se pueden comentar episodios específicos)
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Relación polimórfica: Likes (se pueden dar like a episodios)
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
