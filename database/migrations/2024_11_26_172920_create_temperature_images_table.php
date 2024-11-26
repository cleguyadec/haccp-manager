<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('temperature_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fridge_id')->constrained()->onDelete('cascade'); // Lien vers le frigo
            $table->string('image_path'); // Chemin de l'image
            $table->float('temperature')->nullable(); // Température extraite (par OCR)
            $table->timestamp('captured_at'); // Date et heure de la capture
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temperature_images');
    }
};
