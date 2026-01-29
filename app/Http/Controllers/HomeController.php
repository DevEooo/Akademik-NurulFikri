<?php

namespace App\Http\Controllers;

use App\Models\ManajemenKonten;
use App\Models\Page; // Gunakan Model yang dipakai di Resource ManajemenKonten
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $page = ManajemenKonten::where('slug', 'home')
                    ->where('is_published', true)
                    ->first();
 
        if (!$page) {
            // If no home page in ManajemenKonten, create a default page
            $page = (object) [
                'title' => 'Beranda',
                'content' => []
            ];
        }

        return view('wikipress.home', compact('page'));
    }
}