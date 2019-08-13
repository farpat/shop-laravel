<?php

Route::post('/cart-items', 'Api\CartController@storeItem')
    ->name('cart-items.store');

Route::patch('/cart-items/{productReferenceId}', 'Api\CartController@updateItem')
    ->where('productReferenceId', '\d+')
    ->name('cart-items.update');

Route::delete('/cart-items/{productReferenceId}', 'Api\CartController@destroyItem')
    ->where('productReferenceId', '\d+')
    ->name('cart-items.destroy');
