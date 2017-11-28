<?php

use Faker\Generator as Faker;




$factory->define(App\Game::class, function (Faker $faker) {
    $year = mt_rand(2009, 2017);
    $month = mt_rand(1, 12);
    $day = mt_rand(1, 28);
    $hour = mt_rand(0, 24);
    $minute = mt_rand(0, 60);
    $start_dt = \Carbon\Carbon::create($year, $month, $day, $hour, $minute, 0);
    $hour = $hour + mt_rand(1, 2);
    $minute = mt_rand(0, 60);
    $end_dt = \Carbon\Carbon::create($year, $month, $day, $hour, $minute, 0);

    return [
        'startDatetime' => $start_dt,
        'endDatetime' => $end_dt,
        'winner_id' => $faker->numberBetween(1,15),
    ];
});
