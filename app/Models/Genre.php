<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Al crear un género, generamos automáticamente el slug.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($genre) {
            $genre->slug = Str::slug($genre->name);
        });
    }

    /**
     * Películas que pertenecen a este género.
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    /**
     * Series que pertenecen a este género.
     */
    public function series()
    {
        return $this->belongsToMany(Series::class);
    }
}
