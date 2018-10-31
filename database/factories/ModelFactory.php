<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Domain::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->domainName(),
        'status_code' => $faker->randomElement(array(200, 500, 403, 401)),
        'content_length' => $faker->numberBetween(10,20000),
        'body' => $faker->text(500),
    ];
});
