<?php

use Illuminate\Database\Seeder;

class RecipeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        App\RecipeType::create(['name' => 'breakfast']);
        App\RecipeType::create(['name' => 'smoothie']);
        App\RecipeType::create(['name' => 'bar']);
        App\RecipeType::create(['name' => 'dinner']);
    }
}
