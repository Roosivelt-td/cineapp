<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'rateable_id', 'rateable_type', 'score', 'review'
    ];

    // Relación polimórfica inversa (puede calificar Movie, Series)
    public function rateable()
    {
        return $this->morphTo();
    }

    // Relación: Una calificación pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
