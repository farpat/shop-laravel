<?php

Route::get('/categories/{slug}-{category}', 'CategoryController@show')
    ->name('categories.show')
    ->where('slug', '[a-z0-9-]+')
    ->where('category', '\d+');

Route::get('/categories', 'CategoryController@index')
    ->name('categories.index');
