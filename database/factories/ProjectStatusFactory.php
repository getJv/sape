<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\ProjectStatus;

$factory->define(ProjectStatus::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => Str::random(10),
        'active' => true
    ];
});
