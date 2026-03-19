<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Series;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Dashboard principal del administrador.
     */
    public function index()
    {
        // Estadísticas básicas
        $moviesCount = Movie::count();
        $seriesCount = Series::where('type', 'serie')->count();
        $novelasCount = Series::where('type', 'novela')->count();
        $usersCount = User::count();

        // Últimas 5 películas agregadas
        $latestMovies = Movie::latest()->take(5)->get();

        return view('admin.index', compact('moviesCount', 'seriesCount', 'novelasCount', 'usersCount', 'latestMovies'));
    }

    /**
     * Muestra el formulario para registrar una nueva película.
     */
    public function createMovie()
    {
        return view('admin.movies.create');
    }

    /**
     * Almacena una nueva película.
     */
    public function storeMovie(Request $request)
    {
        // Validaciones
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster_url' => 'required|url',
            'video_url' => 'required|url',
            'is_adult' => 'boolean',
            'release_year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'duration' => 'nullable|integer',
            'genres' => 'nullable|string', // Se espera separado por comas
        ]);

        // Convertir géneros de string "Acción, Drama" a array ["Acción", "Drama"]
        if ($request->has('genres')) {
            $genresArray = array_map('trim', explode(',', $request->genres));
            $validated['genres'] = $genresArray;
        }

        Movie::create($validated);

        return redirect()->route('admin.index')->with('success', 'Película registrada correctamente.');
    }

    // Aquí irían métodos similares para Series y Novelas...
    // createSeries(), storeSeries(), etc.
}
