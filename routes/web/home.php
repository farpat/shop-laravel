<?php

Route::get('/', 'HomeController@index')->name('home.index');
Route::get('/search', 'HomeController@search')->name('home.search');
