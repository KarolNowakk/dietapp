<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('recipe_id')->unsigned();
            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->date('meal_date');
            $table->unsignedTinyInteger('meal_number');
            $table->time('meal_hour');
            $table->float('factor');
            $table->unsignedSmallInteger('proteins');
            $table->unsignedSmallInteger('carbs');
            $table->unsignedSmallInteger('fats');
            $table->unsignedSmallInteger('saturated_fats');
            $table->unsignedSmallInteger('polysaturated_fats');
            $table->unsignedSmallInteger('monosaturated_fats');
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
        Schema::dropIfExists('meals');
    }
}
