<?php

use Illuminate\Database\Seeder;

class RecipeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\RecipeType::class, 5)->create();
    }
}
