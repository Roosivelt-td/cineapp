<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'series_id',
        'name',
        'number'
    ];

    /**
     * Una temporada pertenece a una serie.
     */
    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Una temporada tiene muchos episodios.
     */
    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
