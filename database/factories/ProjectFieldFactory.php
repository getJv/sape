<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\ProjectField;

$factory->define(ProjectField::class, function (Faker $faker) {
    return [
        'project_id' => $faker->unique()->randomNumber(),
        'field_id' => $faker->unique()->randomNumber(),
        'active' => true
    ];
});
