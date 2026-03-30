<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Season;
use App\Models\Series;

class SeasonController extends Controller
{
    /**
     * API: Obtiene las temporadas de una serie con sus capítulos.
     */
    public function getSeasons(Series $series)
    {
        return response()->json($series->seasons()->with('episodes')->orderBy('number')->get());
    }

    /**
     * API: Registra una nueva temporada.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'series_id' => 'required|exists:series,id',
            'number' => 'required|integer',
            'name' => 'nullable|string|max:255',
        ]);

        $season = Season::create([
            'series_id' => $validated['series_id'],
            'number' => $validated['number'],
            'name' => $validated['name'] ?: 'Temporada ' . $validated['number'],
        ]);

        return response()->json($season->load('episodes'), 201);
    }

    /**
     * API: Elimina una temporada.
     */
    public function destroy(Season $season)
    {
        $season->delete();
        return response()->json(['message' => 'Temporada eliminada']);
    }
}
