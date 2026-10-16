<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $cafeIds = $request->user()->favorites()->pluck('cafe_id');
        $cafes = Cafe::withRating()->whereIn('id', $cafeIds)->get();

        return view('favorite.index', compact('cafes'));
    }

    public function toggle(Request $request, Cafe $cafe)
    {
        $user = $request->user();
        $favorite = $user->favorites()->where('cafe_id', $cafe->id)->first();

        if ($favorite) {
            $favorite->delete();
            $status = false;
            $message = 'Dihapus dari favorit';
        } else {
            $user->favorites()->create(['cafe_id' => $cafe->id]);
            $status = true;
            $message = 'Disimpan ke favorit';
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => $status, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
