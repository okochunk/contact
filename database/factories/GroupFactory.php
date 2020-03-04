<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Group::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});
