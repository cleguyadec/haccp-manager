<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('container_size'); // Supprimer la colonne
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('container_size')->nullable(); // Ajouter à nouveau la colonne si nécessaire
        });
    }
};