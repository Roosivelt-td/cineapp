<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Series;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio con contenido categorizado.
     */
    public function index()
    {
        $featuredMovie = Movie::where('is_adult', false)->inRandomOrder()->first();

        // Obtener todos los géneros que tienen contenido asociado
        $genres = Genre::whereHas('movies')
            ->orWhereHas('series')
            ->orderBy('name')
            ->get();

        // Organizar el contenido por género y tipo
        $sections = [];
        foreach ($genres as $genre) {
            $movies = $genre->movies()->where('is_adult', false)->take(15)->get();
            $series = $genre->series()->where('type', 'serie')->where('is_adult', false)->take(15)->get();
            $novelas = $genre->series()->where('type', 'novela')->where('is_adult', false)->take(15)->get();
            $animes = $genre->series()->where('type', 'anime')->where('is_adult', false)->take(15)->get();

            if ($movies->count() > 0 || $series->count() > 0 || $novelas->count() > 0 || $animes->count() > 0) {
                $sections[] = [
                    'genre' => $genre,
                    'movies' => $movies,
                    'series' => $series,
                    'novelas' => $novelas,
                    'animes' => $animes,
                ];
            }
        }

        $allGenres = Genre::orderBy('name')->pluck('name');

        return view('home', compact('featuredMovie', 'sections', 'allGenres'));
    }
}
