<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'body', 'commentable_id', 'commentable_type', 'likes_count'
    ];

    // Relación polimórfica inversa (puede pertenecer a Movie, Episode, etc.)
    public function commentable()
    {
        return $this->morphTo();
    }

    // Relación: Un comentario pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica: Likes (los comentarios también pueden recibir likes)
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
