<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Borramos si existen para evitar conflictos
        Schema::dropIfExists('episodes');
        Schema::dropIfExists('seasons');

        // Tabla de Temporadas
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained('series')->onDelete('cascade');
            $table->integer('number');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        // Tabla de Episodios
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->onDelete('cascade');
            $table->integer('number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('poster_url')->nullable(); // <-- Nuevo campo para miniatura del episodio
            $table->string('video_url')->nullable();
            $table->integer('duration')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
        Schema::dropIfExists('seasons');
    }
};
