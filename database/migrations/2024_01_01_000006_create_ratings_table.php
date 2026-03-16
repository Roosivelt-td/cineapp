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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Polimorfismo: Para calificar Movies, Series o Episodes
            $table->unsignedBigInteger('rateable_id');
            $table->string('rateable_type');

            $table->tinyInteger('score'); // De 1 a 5
            $table->text('review')->nullable(); // Reseña opcional

            $table->timestamps();

            // Un usuario solo califica una vez el mismo contenido
            $table->unique(['user_id', 'rateable_id', 'rateable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
