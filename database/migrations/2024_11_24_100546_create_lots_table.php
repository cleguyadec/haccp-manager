<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->date('expiration_date')->nullable(); // Date de péremption
            $table->integer('stock')->default(0); // Stock du lot
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lots');
    }
};
