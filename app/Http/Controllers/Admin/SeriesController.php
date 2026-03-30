<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Genre;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        // Detectamos el tipo desde la ruta (definido en web.php defaults)
        $type = $request->route('type') ?? $request->get('type', 'serie');

        $pageTitle = $type === 'anime' ? 'Animes' : ucfirst($type) . 's';
        $genres = Genre::orderBy('name')->get();

        return view('admin.series.index', compact('type', 'pageTitle', 'genres'));
    }

    public function showContent(Series $series)
    {
        if (!Schema::hasTable('seasons')) {
            return "Error: Ejecuta 'php artisan migrate' para crear las tablas de temporadas.";
        }

        $series->load(['seasons' => function($q) {
            $q->orderBy('number', 'asc');
        }, 'seasons.episodes' => function($q) {
            $q->orderBy('number', 'asc');
        }]);

        return view('admin.series.content', compact('series'));
    }

    public function getSeries(Request $request)
    {
        $type = $request->get('type', 'serie');
        $query = Series::with('genres')->where('type', $type);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return response()->json($query->withCount('seasons')->orderBy('created_at', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:serie,novela,anime',
            'poster_url' => 'nullable|string',
            'banner_url' => 'nullable|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        try {
            return DB::transaction(function () use ($request) {
                $series = new Series();
                $series->title = $request->title;
                $series->type = $request->type;
                $series->description = $request->description;
                $series->poster_url = $request->poster_url;
                $series->banner_url = $request->banner_url;
                $series->release_year = $request->release_year ?: null;
                $series->save();

                if ($request->has('genres')) $series->genres()->sync($request->genres);

                return response()->json($series->load('genres'), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error DB', 'debug' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Series $series)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        try {
            return DB::transaction(function () use ($request, $series) {
                $series->title = $request->title;
                $series->description = $request->description;
                $series->poster_url = $request->poster_url;
                $series->banner_url = $request->banner_url;
                $series->release_year = $request->release_year ?: null;
                $series->save();

                if ($request->has('genres')) {
                    $series->genres()->sync($request->genres);
                }

                return response()->json($series->load('genres'));
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error DB', 'debug' => $e->getMessage()], 500);
        }
    }

    public function destroy(Series $series)
    {
        try {
            $series->genres()->detach();
            $series->delete();
            return response()->json(['message' => 'Eliminado']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error DB', 'debug' => $e->getMessage()], 500);
        }
    }

    public function storeSeason(Request $request, Series $series)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        try {
            $season = new Season();
            $season->series_id = $series->id;
            $season->number = (int)$request->number;
            $season->name = $request->name ?: ('Temporada ' . $request->number);
            $season->save();
            return response()->json($season, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error DB', 'debug' => $e->getMessage()], 500);
        }
    }
}
