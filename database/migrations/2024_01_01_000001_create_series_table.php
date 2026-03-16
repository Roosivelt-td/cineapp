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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('poster_url')->nullable();
            $table->enum('type', ['serie', 'novela']); // 'serie' o 'novela'
            $table->year('release_year')->nullable();
            $table->json('authors')->nullable(); // Autores/Actores
            $table->json('genres')->nullable(); // Géneros
            $table->boolean('is_adult')->default(false); // Por si acaso hay series +18
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
        Schema::dropIfExists('series');
    }
};
