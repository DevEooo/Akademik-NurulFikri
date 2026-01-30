<?php

namespace App\Http\Controllers;

use App\Models\ManajemenKonten;

class HomeController extends Controller
{
    public function home()
    {
        $page = ManajemenKonten::where('slug', 'home')
                    ->where('is_published', true)
                    ->first();
 
        if (!$page) {
            // Create a non-persisted model instance so Blade can access model-like properties
            $page = new ManajemenKonten([
                'title' => 'Beranda',
                'slug' => 'home',
                'konten' => []
            ]);
        }

        return view('interface.page', compact('page'));
    }
    public function show($slug)
    {
        $page = ManajemenKonten::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('interface.page', compact('page'));
    }
}