<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDefaultLocationMaison extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Vérifie si l'emplacement "Maison" existe déjà pour éviter les doublons
        if (!DB::table('locations')->where('name', 'Maison')->exists()) {
            DB::table('locations')->insert([
                'name' => 'Maison',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprime l'emplacement "Maison" lors du rollback
        DB::table('locations')->where('name', 'Maison')->delete();
    }
}
