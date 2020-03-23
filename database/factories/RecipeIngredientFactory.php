<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\RecipeIngredient::class, function (Faker $faker) {
    return [
        'recipe_id'=>random_int(1,30),
        'product_id'=>random_int(1,100),
        'amount'=>random_int(1,50),
        'ing_type_id'=>random_int(1,3)
    ];
});
