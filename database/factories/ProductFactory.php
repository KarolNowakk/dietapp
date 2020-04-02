<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name'=> $faker->word(),
        'kcal' => random_int(70,400),
        'proteins'=> mt_rand(1,2700) / 100,
        'carbs'=> mt_rand(1,2700) / 100,
        'fats'=> mt_rand(1,2700) / 100,
        'saturated_fats'=> mt_rand(1,700) / 100,
        'polysaturated_fats'=> mt_rand(1,700) / 100,
        'monosaturated_fats'=> mt_rand(1,700) / 100,
        'is_private'=> false,
        'user_id' => 1,
    ];
});
