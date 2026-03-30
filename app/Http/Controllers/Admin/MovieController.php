<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function index()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.movies.index', compact('genres'));
    }

    public function getMovies(Request $request)
    {
        $query = Movie::with('genres');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        Log::info('Iniciando registro de película', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'release_year' => 'nullable|integer',
            'duration' => 'nullable|integer',
            'poster_url' => 'nullable|string',
            'video_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                // Creamos la película sin tocar la columna antigua 'genres'
                $movie = new Movie();
                $movie->title = $request->title;
                $movie->description = $request->description;
                $movie->poster_url = $request->poster_url;
                $movie->video_url = $request->video_url;
                $movie->release_year = $request->release_year ?: null;
                $movie->duration = $request->duration ?: null;

                // NO asignamos nada a $movie->genres aquí para evitar el error de Array to String

                $movie->save();

                // Guardamos los géneros en la tabla de relación (Muchos a Muchos)
                if ($request->has('genres') && is_array($request->genres)) {
                    $movie->genres()->sync($request->genres);
                }

                Log::info('Película registrada con éxito ID: ' . $movie->id);
                return response()->json($movie->load('genres'), 201);
            });
        } catch (\Exception $e) {
            Log::error('Error fatal al registrar película: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error en la base de datos',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Movie $movie)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            return DB::transaction(function () use ($request, $movie) {
                $movie->title = $request->title;
                $movie->description = $request->description;
                $movie->poster_url = $request->poster_url;
                $movie->video_url = $request->video_url;
                $movie->release_year = $request->release_year ?: null;
                $movie->duration = $request->duration ?: null;
                $movie->save();

                if ($request->has('genres')) {
                    $movie->genres()->sync($request->genres);
                }

                return response()->json($movie->load('genres'));
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar', 'debug' => $e->getMessage()], 500);
        }
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
