<?php

use Faker\Generator as Faker;


$factory->define(App\Player::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'rating' => $faker->numberBetween(1200, 2890),
    ];
});
