<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Series;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear una Serie de ejemplo
        $serie = Series::create([
            'title' => 'Breaking Bad',
            'description' => 'Un profesor de química diagnosticado con cáncer se convierte en fabricante de metanfetamina para asegurar el futuro de su familia.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Breaking+Bad',
            'type' => 'serie',
            'release_year' => 2008,
            'authors' => ['Vince Gilligan'],
            'genres' => ['Crimen', 'Drama', 'Thriller'],
            'is_adult' => false,
            'views_count' => 5000,
            'likes_count' => 1200,
            'rating_avg' => 4.9
        ]);

        // Crear una Temporada para la Serie
        $temporada1 = $serie->seasons()->create([
            'name' => 'Temporada 1',
            'number' => 1
        ]);

        // Crear Capítulos para la Temporada 1
        $temporada1->episodes()->createMany([
            [
                'title' => 'Piloto',
                'description' => 'Walter White es diagnosticado con cáncer terminal y decide dar un giro drástico a su vida.',
                'number' => 1,
                'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'duration' => 58,
            ],
            [
                'title' => 'El gato está en la bolsa...',
                'description' => 'Walter y Jesse intentan lidiar con el cuerpo de Krazy-8 y Emilio.',
                'number' => 2,
                'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'duration' => 48,
            ]
        ]);


        // 2. Crear una Novela de ejemplo
        $novela = Series::create([
            'title' => 'Yo soy Betty, la fea',
            'description' => 'Beatriz Pinzón Solano es una economista joven y brillante, pero poco atractiva, que trabaja en una empresa de moda.',
            'poster_url' => 'https://via.placeholder.com/300x450.png?text=Betty+La+Fea',
            'type' => 'novela',
            'release_year' => 1999,
            'authors' => ['Fernando Gaitán'],
            'genres' => ['Telenovela', 'Comedia', 'Romance'],
            'is_adult' => false,
            'views_count' => 8000,
            'likes_count' => 2500,
            'rating_avg' => 4.7
        ]);

        $temporadaNovela = $novela->seasons()->create([
            'name' => 'Capítulos Completos',
            'number' => 1
        ]);

        $temporadaNovela->episodes()->create([
            'title' => 'Capítulo 1: La entrevista',
            'description' => 'Betty llega a Ecomoda buscando trabajo.',
            'number' => 1,
            'video_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'duration' => 45,
        ]);
    }
}
