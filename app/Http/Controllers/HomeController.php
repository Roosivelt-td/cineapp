<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Series;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio con películas destacadas y series.
     */
    public function index()
    {
        $movieQuery = Movie::query();
        $seriesQuery = Series::query();
        $featuredQuery = Movie::query();

        if (!Auth::check()) {
            $movieQuery->where('is_adult', false);
            $seriesQuery->where('is_adult', false);
            $featuredQuery->where('is_adult', false);
        }

        $recentMovies = $movieQuery->orderBy('created_at', 'desc')->take(10)->get();
        $popularSeries = $seriesQuery->orderBy('views_count', 'desc')->take(10)->get();

        // Asegurarnos de que haya al menos una película para destacar
        if ($featuredQuery->count() > 0) {
            $featuredMovie = $featuredQuery->inRandomOrder()->first();
        } else {
            $featuredMovie = null; // O una película por defecto si lo prefieres
        }

        // Obtener todos los géneros únicos de películas y series
        $movieGenres = Movie::select('genres')->distinct()->get()->pluck('genres')->flatten();
        $seriesGenres = Series::select('genres')->distinct()->get()->pluck('genres')->flatten();
        $allGenres = $movieGenres->merge($seriesGenres)->unique()->sort()->values();

        return view('home', compact('recentMovies', 'popularSeries', 'featuredMovie', 'allGenres'));
    }
}
