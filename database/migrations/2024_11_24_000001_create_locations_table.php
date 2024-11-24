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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de l'emplacement
            $table->timestamps();
        });
    
        Schema::create('lot_location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0); // Stock dans cet emplacement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_location'); // Supprime la table pivot
        Schema::dropIfExists('locations');    // Supprime la table locations
    }
};
