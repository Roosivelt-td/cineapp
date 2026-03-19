<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Middleware\EnsureUserIsAdmin;

// --- Rutas Públicas ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Redirección Inteligente ---
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- Perfil de Usuario ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rutas de Administración ---
Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'index'])->name('index');

        // === PELÍCULAS ===
        Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
        // API:
        Route::get('/api/movies/list', [MovieController::class, 'getMovies'])->name('movies.list');
        Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
        Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
        Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');

        // === SERIES, NOVELAS, ANIMES ===
        // Usamos el mismo controlador pero pasamos el tipo por query string (?type=...)
        // O mejor: Rutas específicas que fijan el tipo
        Route::get('/series', [SeriesController::class, 'index'])->defaults('type', 'serie')->name('series.index');
        Route::get('/novelas', [SeriesController::class, 'index'])->defaults('type', 'novela')->name('novelas.index');
        Route::get('/animes', [SeriesController::class, 'index'])->defaults('type', 'anime')->name('animes.index');

        // API para Series (Maneja todo por tipo):
        Route::get('/api/series/list', [SeriesController::class, 'getSeries'])->name('series.list');
        Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
        Route::put('/series/{series}', [SeriesController::class, 'update'])->name('series.update');
        Route::delete('/series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');

    });

require __DIR__.'/auth.php';
