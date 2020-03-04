<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Contact::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'group_id'   => 1,
        'avatar'     => '1583294221_5e5f270dbd18c.jpg',
        'last_name'  => $faker->lastName,
        'country'    => 102,
        'state'      => 1669,
        'city'       => 21479,
        'address'    => $faker->address,
        'zip'        => 15151,
        'email'      => $faker->unique()->safeEmail,
        'phone'      => $faker->e164PhoneNumber,
        'note'       => $faker->sentence($nbWords = 6, $variableNbWords = true),
    ];
});
