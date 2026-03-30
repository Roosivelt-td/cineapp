<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EpisodeController extends Controller
{
    public function store(Request $request, Season $season)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
            'video_url' => 'required|string',
            'poster_url' => 'nullable|string', // Validación del póster
            'duration' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        try {
            return DB::transaction(function () use ($request, $season) {
                $episode = new Episode();
                $episode->season_id = $season->id;
                $episode->title = $request->title;
                $episode->number = $request->number;
                $episode->description = $request->description;
                $episode->poster_url = $request->poster_url; // Guardado
                $episode->video_url = $request->video_url;
                $episode->duration = $request->duration ?: null;
                $episode->save();

                return response()->json($episode, 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error DB', 'debug' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Episode $episode)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
            'video_url' => 'required|string',
            'poster_url' => 'nullable|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        try {
            $episode->title = $request->title;
            $episode->number = $request->number;
            $episode->description = $request->description;
            $episode->poster_url = $request->poster_url; // Actualizado
            $episode->video_url = $request->video_url;
            $episode->duration = $request->duration ?: null;
            $episode->save();

            return response()->json($episode);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error DB', 'debug' => $e->getMessage()], 500);
        }
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();
        return response()->json(['message' => 'Eliminado']);
    }

    public function destroySeason(Season $season)
    {
        $season->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
