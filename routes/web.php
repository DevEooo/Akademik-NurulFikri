<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Models\ManajemenKonten;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/page/{slug}', [HomeController::class, 'show'])->name('page.show');