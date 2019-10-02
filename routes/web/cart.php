<?php

//Cart management
Route::post('/cart-items', 'Front\CartController@storeItem')
    ->name('cart-items.store');

Route::patch('/cart-items/{productReferenceId}', 'Front\CartController@updateItem')
    ->where('productReferenceId', '\d+')
    ->name('cart-items.update');

Route::delete('/cart-items/{productReferenceId}', 'Front\CartController@destroyItem')
    ->where('productReferenceId', '\d+')
    ->name('cart-items.destroy');


//Form
Route::get('/purchase', 'Front\CartController@showPurchaseForm')->name('cart.purchase');
Route::post('/purchase', 'Front\CartController@purchase');

//Billing
Route::get('/billing/export/{billing}', 'Front\CartController@exportBilling')
    ->where('billing', '\d{4}-\d{2}-\d+')
    ->name('cart.export_billing');

Route::get('/billing/view/{billing}', 'Front\CartController@viewBilling')
    ->where('billing', '\d{4}-\d{2}-\d+')
    ->name('cart.view_billing');