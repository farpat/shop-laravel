<?php

Route::get('/categories/{slug}-{category}', 'Front\CategoryController@show')
    ->name('categories.show')
    ->where('slug', '[a-z0-9-]+')
    ->where('category', '\d+');

Route::get('/categories', 'Front\CategoryController@index')
    ->name('categories.index');
