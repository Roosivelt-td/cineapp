<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Series;

class SeriesController extends Controller
{
    /**
     * Muestra la página de gestión filtrada por tipo.
     */
    public function index(Request $request)
    {
        // Determinamos qué tipo estamos viendo basado en la URL o parámetro
        // Por defecto 'serie', pero puede ser 'novela' o 'anime'
        $type = $request->query('type', 'serie');

        // Título dinámico para la vista
        $pageTitle = ucfirst($type) . 's'; // Ej: Series, Novelas
        if ($type === 'anime') $pageTitle = 'Animes';

        return view('admin.series.index', compact('type', 'pageTitle'));
    }

    /**
     * API: Obtiene la lista filtrada por tipo.
     */
    public function getSeries(Request $request)
    {
        $type = $request->query('type', 'serie');
        $query = Series::where('type', $type);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('release_year', 'like', "%{$search}%");
            });
        }

        $series = $query->withCount('seasons', 'episodes') // Contamos temporadas y caps
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return response()->json($series);
    }

    /**
     * API: Almacena una nueva serie/novela/anime.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:serie,novela,anime',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url',
            'release_year' => 'nullable|integer',
            'genres' => 'nullable|array',
        ]);

        $series = Series::create($validated);

        return response()->json($series, 201);
    }

    /**
     * API: Actualiza.
     */
    public function update(Request $request, Series $series)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url',
            'release_year' => 'nullable|integer',
            'genres' => 'nullable|array',
        ]);

        $series->update($validated);

        return response()->json($series);
    }

    /**
     * API: Elimina.
     */
    public function destroy(Series $series)
    {
        $series->delete();
        return response()->json(['message' => 'Eliminado correctamente.']);
    }
}
