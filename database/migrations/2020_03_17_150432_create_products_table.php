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
            $table->float('kcal')->unsigned();
            $table->float('proteins')->unsigned();
            $table->float('carbs')->unsigned();
            $table->float('fats')->unsigned();
            $table->float('saturated_fats')->unsigned();
            $table->float('polysaturated_fats')->unsigned();
            $table->float('monosaturated_fats')->unsigned();
            $table->boolean('is_private')->default(false);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullable();
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
