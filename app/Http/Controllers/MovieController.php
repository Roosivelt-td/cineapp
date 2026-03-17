<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Muestra el catálogo de películas.
     */
    public function index(Request $request)
    {
        $query = Movie::query();

        // Filtro por género si se solicita
        if ($request->has('genre')) {
            $genre = $request->input('genre');
            // Como genres es un JSON array, usamos whereJsonContains
            $query->whereJsonContains('genres', $genre);
        }

        // Filtro para excluir +18 si el usuario no está logueado o verificado
        // (Esto dependerá de cómo manejes la autenticación en el frontend)
        // Por defecto, la API pública podría ocultar +18
        if ($request->has('hide_adult') && $request->input('hide_adult') == 'true') {
            $query->where('is_adult', false);
        }

        // Ordenamiento (por defecto las más nuevas)
        $movies = $query->orderBy('release_year', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(12); // Paginación de 12 en 12

        return response()->json($movies);
    }

    /**
     * Muestra el detalle de una película específica.
     */
    public function show($id)
    {
        $movie = Movie::with(['comments.user', 'comments.likes', 'likes', 'ratings'])->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Calcular promedio de ratings dinámicamente si se desea,
        // o usar el campo almacenado rating_avg

        return response()->json($movie);
    }

    /**
     * Crea una nueva película (Solo Admin).
     */
    public function store(Request $request)
    {
        // Validación básica
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'is_adult' => 'boolean',
            // ... otras validaciones
        ]);

        $movie = Movie::create($request->all());

        return response()->json($movie, 201);
    }

    /**
     * Actualiza una película (Solo Admin).
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) return response()->json(['message' => 'No encontrada'], 404);

        $movie->update($request->all());
        return response()->json($movie);
    }

    /**
     * Elimina una película (Solo Admin).
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        if (!$movie) return response()->json(['message' => 'No encontrada'], 404);

        $movie->delete();
        return response()->json(['message' => 'Película eliminada']);
    }
}
