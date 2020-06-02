<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Project;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => Str::random(10),
        'order' => $faker->unique()->randomNumber(),
        'active' => true
    ];
});
