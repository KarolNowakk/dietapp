<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\IngType::class, function (Faker $faker) {
    return [
        'name'=>$faker->word()
    ];
});
