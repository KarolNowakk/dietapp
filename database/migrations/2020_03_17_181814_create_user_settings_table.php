<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('required_kcal');
            $table->unsignedSmallInteger('required_proteins');
            $table->unsignedSmallInteger('required_carbs');
            $table->unsignedSmallInteger('required_fats');
            $table->unsignedSmallInteger('meals_per_day');
            $table->string('count_by')->nullable();
            $table->time('start');
            $table->time('end');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
