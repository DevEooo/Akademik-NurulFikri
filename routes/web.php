<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/welcome');

Route::get('/welcome', function () {
    return view('welcome');
});
