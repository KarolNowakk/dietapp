<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SubstancesTableSeeder::class,
            UsersTableSeeder::class,
            ProductsTableSeeder::class,
            RecipeTypesTableSeeder::class,
            RecipesTableSeeder::class,
            UserSettingsTableSeeder::class,
        ]);
    }
}
