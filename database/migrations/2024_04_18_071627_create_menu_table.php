<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->String('nama');
            $table->integer('harga');
            $table->String('gambar');
            $table->timestamps();

            $table->unsignedBigInteger('cafe_id')->nullable();
            $table->unsignedBigInteger('user_id');
            
            // Foreign key dipindahkan ke migrasi terpisah
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
};
