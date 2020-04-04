<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\ProjectWorkflow;

$factory->define(ProjectWorkflow::class, function (Faker $faker) {

    return [
        'project_id' => $faker->unique()->randomNumber(),
        'order' => $faker->unique()->randomNumber(),
        'old_status_id' => $faker->unique()->randomNumber(),
        'new_status_id' => $faker->unique()->randomNumber()
    ];
});
