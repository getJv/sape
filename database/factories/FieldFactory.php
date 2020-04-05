<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Field;

$factory->define(Field::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'type' => $faker->unique()->name,
        'description' => Str::random(10),
        'min' => $faker->unique()->randomNumber(),
        'max' => $faker->unique()->randomNumber(),
        'mask' => Str::random(10),
        'active' => true,
    ];
});
