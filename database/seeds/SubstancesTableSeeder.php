<?php

use Illuminate\Database\Seeder;

class SubstancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        App\Substance::create(['name' => 'gluten']);
        App\Substance::create(['name' => 'dairy']);
        App\Substance::create(['name' => 'lactose']);
        App\Substance::create(['name' => 'nuts']);
        App\Substance::create(['name' => 'meat']);
        App\Substance::create(['name' => 'meat_related']);
        App\Substance::create(['name' => 'fish']);
    }
}
