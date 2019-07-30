<?php

/* @var $factory Factory */

use App\Models\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Image::class, function (Faker $faker) {
    $id = random_int(1, 100);
    $normalSize = [1000, 400];
    $thumbSize = [100, 40];

    $url = "https://picsum.photos/id/{$id}/{$normalSize[0]}/{$normalSize[1]}/";
    $urlThumbnail = "https://picsum.photos/id/{$id}/{$thumbSize[0]}/{$thumbSize[1]}/";
    $alt = $faker->sentence;

    return [
        'url'           => $url,
        'alt'           => $alt,
        'url_thumbnail' => $urlThumbnail,
        'alt_thumbnail' => $alt,
    ];
});
