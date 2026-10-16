<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToOwner;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ScopesToOwner;

    public function index(Request $request)
    {
        $user = $request->user();
        $cafeIds = $this->cafesFor($user)->pluck('id');
        $reviews = Review::whereIn('cafe_id', $cafeIds);

        $stats = [
            'cafe' => $cafeIds->count(),
            'menu' => Menu::whereIn('cafe_id', $cafeIds)->count(),
            'review' => (clone $reviews)->count(),
            'rating' => round((float) (clone $reviews)->avg('rating'), 1),
        ];

        $distribution = (clone $reviews)
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $latestReviews = (clone $reviews)->with(['user', 'cafe'])->latest()->take(5)->get();

        $pendingSellers = $user->isAdmin()
            ? User::where('role', 'penjual')->where('status', 'pending')->latest()->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'distribution', 'latestReviews', 'pendingSellers'));
    }
}
