<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('kcal');
            $table->unsignedSmallInteger('proteins');
            $table->unsignedSmallInteger('carbs');
            $table->unsignedSmallInteger('fats');
            $table->unsignedSmallInteger('saturated_fats');
            $table->unsignedSmallInteger('polysaturated_fats');
            $table->unsignedSmallInteger('monosaturated_fats');
            $table->boolean('is_private')->default(false);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
