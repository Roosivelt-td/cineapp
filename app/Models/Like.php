<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'likeable_id', 'likeable_type'
    ];

    // Relación polimórfica inversa (puede pertenecer a Movie, Series, Episode, Comment)
    public function likeable()
    {
        return $this->morphTo();
    }

    // Relación: Un like pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
