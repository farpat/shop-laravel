<?php

/* @var $factory Factory */

use App\Models\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    $label = $faker->unique()->words(3, true);
    $slug = Str::slug($label);
    $excerpt = $faker->boolean() ? $faker->paragraph : null;
    $description = $excerpt ? $faker->paragraphs(5, true) : null;


    return [
        'label'       => $label,
        'slug'        => $slug,
        'excerpt'     => $excerpt,
        'description' => $description,
    ];
});
