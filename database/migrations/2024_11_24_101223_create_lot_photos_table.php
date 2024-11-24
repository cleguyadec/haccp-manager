<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lot_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots')->onDelete('cascade');
            $table->string('photo_path'); // Chemin de la photo
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lot_photos');
    }
};

