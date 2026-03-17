<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    /**
     * Muestra el detalle de un capítulo específico.
     */
    public function show($id)
    {
        $episode = Episode::with(['season.series', 'comments.user', 'likes'])
                            ->find($id);

        if (!$episode) {
            return response()->json(['message' => 'Capítulo no encontrado'], 404);
        }

        // Incremento de vistas (si el usuario es único o por sesión,
        // pero aquí simplemente incrementamos)
        $episode->increment('views_count');

        return response()->json($episode);
    }

    /**
     * Crea un nuevo capítulo (Solo Admin).
     */
    public function store(Request $request)
    {
        // Validación básica
        $validated = $request->validate([
            'season_id' => 'required|exists:seasons,id',
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
            'video_url' => 'required|url',
        ]);

        $episode = Episode::create($request->all());

        return response()->json($episode, 201);
    }
}
