<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;
use App\Models\Episode;

class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios y contenido para interactuar
        $user1 = User::where('email', 'juan@cineapp.com')->first();
        $user2 = User::where('email', 'maria@cineapp.com')->first();
        $movie = Movie::first();
        $episode = Episode::first();

        if (!$user1 || !$user2 || !$movie || !$episode) {
            $this->command->info('No se encontraron usuarios o contenido para crear interacciones.');
            return;
        }

        // 1. Comentarios
        $movie->comments()->create([
            'user_id' => $user1->id,
            'body' => '¡Excelente película! Muy recomendada.'
        ]);

        $episode->comments()->create([
            'user_id' => $user2->id,
            'body' => 'El primer capítulo es increíble, te engancha desde el principio.'
        ]);

        // 2. Likes
        $movie->likes()->create(['user_id' => $user1->id]);
        $episode->likes()->create(['user_id' => $user2->id]);
        $episode->likes()->create(['user_id' => $user1->id]); // El episodio tiene 2 likes

        // 3. Calificaciones (Ratings)
        $movie->ratings()->create([
            'user_id' => $user1->id,
            'score' => 5,
            'review' => 'Una obra maestra del cine de acción.'
        ]);

        $movie->ratings()->create([
            'user_id' => $user2->id,
            'score' => 4,
        ]);
    }
}
