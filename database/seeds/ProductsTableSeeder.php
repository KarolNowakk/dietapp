<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(App\Product::class, 100)->create();

        App\Product::all()->each(function ($product) {
            $product->substances()->attach(random_int(1, 7));
        });
    }
}
