<?php

Route::post('/cart-items', 'CartController@storeItem')
    ->name('cart-items.store');

Route::patch('/cart-items/{productReferenceId}', 'CartController@updateItem')
    ->where('productReferenceId', '\d+')
    ->name('cart-items.update');

Route::delete('/cart-items/{productReferenceId}', 'CartController@destroyItem')
    ->where('productReferenceId', '\d+')
    ->name('cart-items.destroy');

Route::get('/purchase', 'CartController@purchase')
    ->name('cart.purchase');
