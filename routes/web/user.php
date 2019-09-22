<?php

Route::get('/profile', 'Front\UserController@profile')->name('user.profile');

Route::get('/informations', 'Front\UserController@showInformationsForm')->name('user.informations');
Route::post('/informations', 'Front\UserController@updateInformations');

Route::get('/password', 'Front\UserController@showPasswordForm')->name('user.password');
Route::post('/password', 'Front\UserController@updatePassword');

Route::get('/billings', 'Front\UserController@billings')->name('user.billings');
