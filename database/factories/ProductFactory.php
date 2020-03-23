<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name'=> $faker->word(),
        'kcal' => random_int(70,400),
        'proteins'=> random_int(1,27),
        'carbs'=> random_int(1,27),
        'fats'=> random_int(1,27),
        'saturated_fats'=> random_int(1,6),
        'polysaturated_fats'=> random_int(1,10),
        'monosaturated_fats'=> random_int(1,4),
        'is_private'=>false
    ];
});
