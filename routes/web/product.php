<?php

Route::get('/categories/{categorySlug}-{categoryId}/{slug}-{product}', 'ProductController@show')
    ->name('products.show')
    ->where('categorySlug', '[a-z0-9-]+')
    ->where('categoryId', '\d+')
    ->where('slug', '[a-z0-9-]+')
    ->where('product', '\d+');

Route::get('/categories', 'CategoryController@index')
    ->name('categories.index');
