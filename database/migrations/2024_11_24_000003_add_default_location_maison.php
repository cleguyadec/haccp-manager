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
        // Vérifie si l'emplacement "maison" existe déjà pour éviter les doublons
        if (!DB::table('locations')->where('name', 'maison')->exists()) {
            DB::table('locations')->insert([
                'name' => 'maison',
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
        // Supprime l'emplacement "maison" lors du rollback
        DB::table('locations')->where('name', 'maison')->delete();
    }
}
