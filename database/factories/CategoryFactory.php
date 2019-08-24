<?php

/* @var $factory Factory */

use App\Models\Category;
use App\Models\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    $label = $faker->words(2, true);
    $slug = Str::slug($label);
    $nomenclature = str_replace('-', '', Str::upper($slug));

    return [
        'label'        => $label,
        'slug'         => $slug,
        'nomenclature' => $nomenclature,
        'description'  => $faker->paragraph,
        'is_last'      => false,
        'image_id'     => $faker->boolean(65) ? factory(Image::class)->create()->id : null
    ];
});
