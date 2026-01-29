<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Models\ManajemenKonten;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/{slug}', function ($slug) {
    
    $page = ManajemenKonten::where('slug', $slug)->where('is_published', true)->firstOrFail();
    
    return view('wikipress.page', compact('page'));
    
})->name('dynamic.page');