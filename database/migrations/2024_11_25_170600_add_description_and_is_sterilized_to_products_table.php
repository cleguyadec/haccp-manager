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
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name'); // Colonne description
            $table->boolean('is_sterilized')->default(false)->after('description'); // Colonne stérilisé
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_sterilized']);
        });
    }
};
