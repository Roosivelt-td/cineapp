<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar rutas API para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider dentro de un grupo que
| contiene el grupo de middleware "api".
|
*/

// --- Rutas Públicas (No requieren Login) ---

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Catálogo de Películas
Route::get('/movies', [MovieController::class, 'index']); // Listar
Route::get('/movies/{id}', [MovieController::class, 'show']); // Ver detalle

// Catálogo de Series y Novelas
Route::get('/series', [SeriesController::class, 'index']); // Listar
Route::get('/series/{id}', [SeriesController::class, 'show']); // Ver detalle

// Ver Capítulos (Podría ser público o privado, lo dejo público)
Route::get('/episodes/{id}', [EpisodeController::class, 'show']);


// --- Rutas Protegidas (Requieren Login - Token Bearer) ---
Route::middleware('auth:sanctum')->group(function () {

    // Usuario
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Interacciones (Comentar, Dar Like, Calificar)
    Route::post('/interactions/like', [InteractionController::class, 'toggleLike']);
    Route::post('/interactions/comment', [InteractionController::class, 'storeComment']);
    Route::post('/interactions/rate', [InteractionController::class, 'storeRating']);


    // --- Rutas de Administrador (Solo Admin puede crear/editar) ---
    // (Aquí podrías agregar un middleware extra 'can:admin')
    Route::group(['middleware' => function ($request, $next) {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return $next($request);
    }], function () {

        // Gestión de Películas
        Route::post('/movies', [MovieController::class, 'store']);
        Route::put('/movies/{id}', [MovieController::class, 'update']);
        Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

        // Gestión de Series
        Route::post('/series', [SeriesController::class, 'store']);
        Route::put('/series/{id}', [SeriesController::class, 'update']);
        Route::delete('/series/{id}', [SeriesController::class, 'destroy']);

        // Gestión de Episodios
        Route::post('/episodes', [EpisodeController::class, 'store']);
        // ... update y delete episodios
    });
});
