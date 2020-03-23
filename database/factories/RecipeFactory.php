<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Recipe::class, function (Faker $faker) {
    return [
        'name'=> $faker->sentence(random_int(1,4)),
        'spices'=> $faker->sentence(random_int(2,5)),
        'steps'=> $faker->paragraph(random_int(2,8)),
        'type_id'=>random_int(1,5),
        'is_private'=>false,
    ];
});
