<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\ProjectEvent;

$factory->define(ProjectEvent::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => Str::random(10),
        'owner_id' => $faker->unique()->randomNumber(),
        'project_workflow_id' =>$faker->unique()->randomNumber(),
        'active' => true
    ];
});
