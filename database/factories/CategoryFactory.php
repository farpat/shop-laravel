<?php

/* @var $factory Factory */

use App\Models\Category;
use App\Models\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    static $labels = ["Books", "Movies", "Music", "Games", "Electronics", "Computers", "Home",
        "Garden", "Tools",
        "Grocery", "Health", "Beauty", "Toys", "Kids", "Baby", "Clothing", "Shoes", "Jewelry", "Sports", "Outdoors",
        "Automotive", "Industrial"];

    $labelKey = array_rand($labels);
    $label = $labels[$labelKey];
    unset($labels[$labelKey]);

    return [
        'label'        => $label,
        'slug'         => strtolower($label),
        'nomenclature' => strtoupper($label),
        'description'  => $faker->sentence(7),
        'is_last'      => false,
        'image_id'     => $faker->boolean(75) ? factory(Image::class)->create()->id : null
    ];
});
