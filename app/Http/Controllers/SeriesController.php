<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    /**
     * Muestra el catálogo de series y novelas.
     */
    public function index(Request $request)
    {
        $query = Series::query();

        // Filtro por tipo: 'serie' o 'novela'
        if ($request->has('type')) {
            $type = $request->input('type');
            if (in_array($type, ['serie', 'novela'])) {
                $query->where('type', $type);
            }
        }

        $series = $query->orderBy('views_count', 'desc')
                        ->paginate(12);

        return response()->json($series);
    }

    /**
     * Muestra el detalle de una serie específica.
     */
    public function show($id)
    {
        // Traer la serie con sus temporadas y episodios
        $series = Series::with(['seasons.episodes'])
                        ->find($id);

        if (!$series) {
            return response()->json(['message' => 'Serie no encontrada'], 404);
        }

        return response()->json($series);
    }

    /**
     * Crea una nueva serie (Solo Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:serie,novela',
            // ... otras validaciones
        ]);

        $series = Series::create($request->all());
        return response()->json($series, 201);
    }

    /**
     * Actualiza una serie (Solo Admin).
     */
    public function update(Request $request, $id)
    {
        $series = Series::find($id);
        if (!$series) return response()->json(['message' => 'No encontrada'], 404);

        $series->update($request->all());
        return response()->json($series);
    }

    /**
     * Elimina una serie (Solo Admin).
     */
    public function destroy($id)
    {
        $series = Series::find($id);
        if (!$series) return response()->json(['message' => 'No encontrada'], 404);

        $series->delete();
        return response()->json(['message' => 'Serie eliminada']);
    }
}
