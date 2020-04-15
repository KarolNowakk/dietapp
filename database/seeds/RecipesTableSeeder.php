<?php

use Illuminate\Database\Seeder;

class RecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(App\Recipe::class, 30)->create();

        App\Recipe::all()->each(function ($recipe) {
            $data = $this->generateRandomProductIds();
            $recipe->products()->attach($data['products'], ['amount' => 2]);

            $recipe->products()->each(function ($attribute) use ($recipe) {
                $recipe->products()->updateExistingPivot($attribute, ['amount' => $this->randomFloat(0, 10, 2)]);
            });
        });
    }

    protected function generateRandomProductIds()
    {
        $products = [];

        for ($i = 0; $i < random_int(3, 8); ++$i) {
            array_push($products, random_int(1, 100));
        }

        return [
            'products' => array_unique($products),
        ];
    }

    protected function randomFloat($min, $max, $round)
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $round);
    }
}
