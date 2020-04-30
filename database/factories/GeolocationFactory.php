<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Domain\Geolocation\Models\Geolocation;
use Domain\Geolocation\Models\Location;

$factory->define(Geolocation::class, static function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->unique()->sha256,
        'location' => new Location($faker->latitude, $faker->longitude),
    ];
});
