<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_adult')->default(false); // Contenido +18
            $table->year('release_year')->nullable();
            $table->integer('duration')->nullable(); // En minutos
            $table->json('authors')->nullable(); // Array de autores/actores
            $table->json('genres')->nullable(); // Array de géneros
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0); // 0.00 a 5.00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
