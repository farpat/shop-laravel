<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Auth::routes(['verify' => true]);

Route::get('spy/{user}', 'Auth\LoginController@spy')->name('spy');
