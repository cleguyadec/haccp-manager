<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToLotsTable extends Migration
{
    public function up()
    {
        Schema::table('lots', function (Blueprint $table) {
            $table->date('production_date')->nullable()->after('product_id'); // Date de production
            $table->date('sterilization_date')->nullable()->after('production_date'); // Date de stérilisation
        });
    }

    public function down()
    {
        Schema::table('lots', function (Blueprint $table) {
            $table->dropColumn(['production_date', 'sterilization_date', 'expiration_date']);
        });
    }
}
