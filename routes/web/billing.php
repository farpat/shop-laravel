<?php

Route::get('/billing/export/{billing}', 'Front\BillingController@export')
    ->where('billing', '\d{4}-\d{2}-\d+')
    ->name('billing.export');

Route::get('/billing/view/{billing}', 'Front\BillingController@view')
    ->where('billing', '\d{4}-\d{2}-\d+')
    ->name('billing.view');