<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Address::class, function (Faker $faker) {
    $line1 = $faker->streetAddress;
    $line2 = $faker->boolean(70) ? ucfirst($faker->words(3, true)) : '';
    $postalCode = $faker->postcode;
    $city = $faker->city;
    $country = $faker->country;
    $latitude = $faker->latitude;
    $longitude = $faker->longitude;


    return [
        'text'        => $line1 . ' ' . $postalCode . ' ' . $city . ', ' . $country,
        'line1'       => $line1,
        'line2'       => $line2,
        'postal_code' => $postalCode,
        'city'        => $city,
        'country'     => $country,
        'latitude'    => $latitude,
        'longitude'   => $longitude,
    ];
});
