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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->String('komentar');
            $table->integer('rating');
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->unsignedBigInteger('cafe_id');
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
        Schema::dropIfExists('ratings');
    }
};
