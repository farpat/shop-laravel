<?php

Route::get('/', 'Front\HomeController@index')->name('home.index');
Route::get('/search', 'Front\HomeController@search')->name('home.search');
