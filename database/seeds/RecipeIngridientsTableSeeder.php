<?php

use Illuminate\Database\Seeder;

class RecipeIngridientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\RecipeIngridient::class,120)->create();
    }
}
