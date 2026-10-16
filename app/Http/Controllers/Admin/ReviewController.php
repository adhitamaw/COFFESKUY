<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToOwner;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ScopesToOwner;

    public function index(Request $request)
    {
        $cafes = $this->cafesFor($request->user())->orderBy('nama')->get(['id', 'nama']);
        $rating = $request->query('rating');
        $cafeId = $request->query('cafe');
        $sort = $request->query('sort', 'terbaru');

        $reviews = Review::with(['user', 'cafe'])
            ->whereIn('cafe_id', $cafes->pluck('id'))
            ->when($rating, fn ($query) => $query->where('rating', $rating))
            ->when($cafeId, fn ($query) => $query->where('cafe_id', $cafeId))
            ->when($sort === 'terlama', fn ($query) => $query->oldest())
            ->when($sort === 'tertinggi', fn ($query) => $query->orderByDesc('rating'))
            ->when($sort === 'terendah', fn ($query) => $query->orderBy('rating'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'cafes', 'rating', 'cafeId', 'sort'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review dihapus.');
    }
}
