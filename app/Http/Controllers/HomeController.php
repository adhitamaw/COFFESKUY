<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Menu;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $topCafes = Cafe::withRating()
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(6)
            ->get();

        $stats = [
            'cafe' => Cafe::count(),
            'menu' => Menu::count(),
            'review' => Review::count(),
        ];

        return view('pages.home', compact('topCafes', 'stats'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
