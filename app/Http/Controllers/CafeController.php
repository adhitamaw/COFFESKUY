<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Review;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'rating');

        $cafes = Cafe::withRating()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")->orWhere('alamat', 'like', "%{$q}%");
                });
            })
            ->when($sort === 'terbaru', fn ($query) => $query->latest())
            ->when($sort !== 'terbaru', fn ($query) => $query->orderByDesc('reviews_avg_rating')->orderByDesc('reviews_count'))
            ->paginate(12)
            ->withQueryString();

        if ($request->is('api/*')) {
            return response()->json(['error' => false, 'list' => $cafes->items()]);
        }

        $favoriteIds = $request->user()?->favorites()->pluck('cafe_id')->all() ?? [];

        return view('cafe.index', compact('cafes', 'q', 'sort', 'favoriteIds'));
    }

    public function show(Request $request, Cafe $cafe)
    {
        $cafe->loadAvg('reviews', 'rating')->loadCount('reviews');

        if ($request->is('api/*')) {
            return response()->json(['error' => false, 'cafe' => $cafe]);
        }

        $menus = $cafe->menus()->orderBy('nama')->get();
        $reviews = $cafe->reviews()->with('user')->latest()->get();

        $user = $request->user();
        $myReview = $user ? $reviews->firstWhere('user_id', $user->id) : null;
        $isFavorite = $user ? $user->favorites()->where('cafe_id', $cafe->id)->exists() : false;

        return view('cafe.show', compact('cafe', 'menus', 'reviews', 'myReview', 'isFavorite'));
    }
}
