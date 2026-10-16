<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private const RULES = [
        'rating' => 'required|integer|between:1,5',
        'komentar' => 'required|string|min:3|max:500',
    ];

    public function store(Request $request)
    {
        $data = $request->validate(self::RULES + ['cafe_id' => 'required|exists:cafe,id']);
        $user = $request->user();

        $exists = Review::where('user_id', $user->id)->where('cafe_id', $data['cafe_id'])->exists();
        if ($exists) {
            return back()->with('error', 'Kamu sudah memberi review untuk cafe ini.');
        }

        Review::create($data + ['user_id' => $user->id, 'status' => 0]);

        return back()->with('success', 'Review terkirim, terima kasih!');
    }

    public function update(Request $request, Review $review)
    {
        abort_unless($review->user_id === $request->user()->id, 403);

        $review->update($request->validate(self::RULES));

        return back()->with('success', 'Review diperbarui.');
    }

    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();
        abort_unless($review->user_id === $user->id || $user->isAdmin(), 403);

        $review->delete();

        return back()->with('success', 'Review dihapus.');
    }
}
