<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Muestra la página de gestión de películas.
     */
    public function index()
    {
        return view('admin.movies.index');
    }

    /**
     * API: Obtiene las películas con paginación y búsqueda.
     */
    public function getMovies(Request $request)
    {
        $query = Movie::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('release_year', 'like', "%{$search}%")
                  ->orWhereJsonContains('genres', $search);
        }

        $movies = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($movies);
    }

    /**
     * API: Almacena una nueva película.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'release_year' => 'nullable|integer',
            'duration' => 'nullable|string',
            'genres' => 'nullable|array',
        ]);

        $movie = Movie::create($validated);

        return response()->json($movie, 201);
    }

    /**
     * API: Actualiza una película existente.
     */
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'release_year' => 'nullable|integer',
            'duration' => 'nullable|string',
            'genres' => 'nullable|array',
        ]);

        $movie->update($validated);

        return response()->json($movie);
    }

    /**
     * API: Elimina una película.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->json(['message' => 'Película eliminada correctamente.']);
    }
}
