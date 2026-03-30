<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Acción',
            'Aventura',
            'Animación',
            'Comedia',
            'Crimen',
            'Documental',
            'Drama',
            'Familia',
            'Fantasía',
            'Historia',
            'Terror',
            'Música',
            'Misterio',
            'Romance',
            'Ciencia Ficción',
            'Suspense',
            'Guerra',
            'Western',
            'Anime',
            'Novela'
        ];

        foreach ($genres as $genreName) {
            Genre::firstOrCreate([
                'name' => $genreName,
                'slug' => Str::slug($genreName)
            ]);
        }
    }
}
