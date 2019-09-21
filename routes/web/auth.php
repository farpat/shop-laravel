<?php

Auth::routes(['verify' => true]);

Route::get('spy/{user}', 'Auth\LoginController@spy')->name('spy');
