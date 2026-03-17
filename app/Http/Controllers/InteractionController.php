<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Series;

class InteractionController extends Controller
{
    /**
     * Permite a un usuario dar o quitar un "Me Gusta".
     */
    public function toggleLike(Request $request)
    {
        $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|string|in:Movie,Series,Episode,Comment',
        ]);

        $class = 'App\\Models\\' . $request->likeable_type;
        $model = $class::find($request->likeable_id);

        if (!$model) {
            return response()->json(['message' => 'Contenido no encontrado'], 404);
        }

        $like = $model->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // Si ya existe el like, lo quitamos
            $like->delete();
            $model->decrement('likes_count');
            return response()->json(['message' => 'Like quitado']);
        } else {
            // Si no existe, lo creamos
            $model->likes()->create(['user_id' => Auth::id()]);
            $model->increment('likes_count');
            return response()->json(['message' => 'Like agregado']);
        }
    }

    /**
     * Permite a un usuario publicar un comentario.
     */
    public function storeComment(Request $request)
    {
        $request->validate([
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:Movie,Episode',
            'body' => 'required|string|min:1',
        ]);

        $class = 'App\\Models\\' . $request->commentable_type;
        $model = $class::find($request->commentable_id);

        if (!$model) {
            return response()->json(['message' => 'Contenido no encontrado'], 404);
        }

        $comment = $model->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return response()->json($comment->load('user'), 201);
    }

    /**
     * Permite a un usuario calificar contenido.
     */
    public function storeRating(Request $request)
    {
        $request->validate([
            'rateable_id' => 'required|integer',
            'rateable_type' => 'required|string|in:Movie,Series',
            'score' => 'required|integer|min:1|max:5',
        ]);

        $class = 'App\\Models\\' . $request->rateable_type;
        $model = $class::find($request->rateable_id);

        if (!$model) {
            return response()->json(['message' => 'Contenido no encontrado'], 404);
        }

        // Actualizar o crear la calificación
        $rating = $model->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['score' => $request->score, 'review' => $request->review]
        );

        // Recalcular el promedio de calificación
        $newAvg = $model->ratings()->avg('score');
        $model->update(['rating_avg' => $newAvg]);

        return response()->json($rating);
    }
}
