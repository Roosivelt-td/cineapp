<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Película 1: Acción
        Movie::create([
            'title' => 'Misión Imposible: Sentencia Mortal',
            'description' => 'Ethan Hunt y su equipo del FMI se embarcan en su misión más peligrosa hasta la fecha: rastrear una nueva arma aterradora.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Mision+Imposible',
            'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4', // Video de ejemplo
            'is_adult' => false,
            'release_year' => 2023,
            'duration' => 163,
            'authors' => ['Tom Cruise', 'Christopher McQuarrie'],
            'genres' => ['Acción', 'Aventura', 'Thriller'],
            'views_count' => 1250,
            'likes_count' => 340,
            'rating_avg' => 4.5
        ]);

        // Película 2: Drama
        Movie::create([
            'title' => 'Oppenheimer',
            'description' => 'La historia del físico estadounidense J. Robert Oppenheimer y su papel en el desarrollo de la bomba atómica.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Oppenheimer',
            'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'is_adult' => false,
            'release_year' => 2023,
            'duration' => 180,
            'authors' => ['Cillian Murphy', 'Christopher Nolan'],
            'genres' => ['Biografía', 'Drama', 'Historia'],
            'views_count' => 2100,
            'likes_count' => 560,
            'rating_avg' => 4.8
        ]);

        // Película 3: +18 (Ejemplo)
        Movie::create([
            'title' => 'Contenido Exclusivo +18',
            'description' => 'Este es un video de demostración para contenido restringido a adultos.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Adult+Content',
            'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'is_adult' => true,
            'release_year' => 2024,
            'duration' => 45,
            'authors' => ['Actor X', 'Director Y'],
            'genres' => ['Adulto'],
            'views_count' => 50,
            'likes_count' => 10,
            'rating_avg' => 3.5
        ]);
    }
}
