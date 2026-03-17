<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'series_id', 'name', 'number',
    ];

    // Relación: Una temporada pertenece a una serie
    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    // Relación: Una temporada tiene muchos capítulos
    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
